<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Raw layer for SECOP II ingestion — Storage Architecture baseline §3
 * (Migration Proposal 1, accepted 2026-10-21).
 *
 * Append-only by design: no application code path may update or delete rows in
 * these tables. Primary enforcement is database privilege separation (the web
 * application role receives no UPDATE/DELETE grants here); see Migration
 * Proposal 1, "Append-only enforcement approach".
 *
 * Idempotency: the unique index on (dataset_id, source_row_id, payload_sha256)
 * makes re-fetching an unchanged row a no-op, while a changed payload for the
 * same source row appends a new row, preserving state history at the raw level
 * (PR-05). Note: PostgreSQL treats NULLs as distinct in unique indexes, so rows
 * with a null source_row_id are not idempotency-blocked — a deliberate choice,
 * because a null identifier is an ingestion anomaly requiring review.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secop_ingestion_batches', function (Blueprint $table) {
            $table->id();
            $table->text('dataset_id')->comment('Source dataset ID, e.g. jbjy-vk9h (ADR-001a Tier A)');
            $table->text('query_signature')->comment('SoQL parameters used — provenance/reproducibility (PR-05)');
            $table->text('cursor_start')->nullable()->comment(':id pagination window start (ADR-002)');
            $table->text('cursor_end')->nullable()->comment(':id pagination window end (ADR-002)');
            $table->bigInteger('rows_fetched')->default(0);
            $table->bigInteger('rows_stored')->default(0)->comment('rows actually appended — idempotency is visible here');
            $table->boolean('app_token_used')->default(false)->comment('evidence only; the token itself is never stored (PR-14)');
            $table->string('status')->default('running')->comment('running | completed | failed');
            $table->text('failure_note')->nullable();
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('finished_at')->nullable();
            $table->timestampsTz();

            $table->index(['dataset_id', 'started_at']);
        });

        Schema::create('secop_raw_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')
                ->constrained('secop_ingestion_batches')
                ->restrictOnDelete()
                ->comment('provenance chain root: raw record -> batch -> query/cursor (PR-05)');
            $table->text('dataset_id')->comment('denormalized for direct queryability');
            // source_row_id (SODA :id) is EXPECTED on every Tier A record.
            // Nullability exists ONLY as a defensive ingestion safeguard so an
            // anomalous source row cannot halt an entire batch mid-load. A null
            // value is an ingestion anomaly requiring review — logged, reported,
            // surfaced; never silently accepted, never faked (PR-06).
            $table->text('source_row_id')->nullable()
                ->comment('SODA :id — expected on all Tier A rows; null = ingestion anomaly requiring review');
            $table->jsonb('payload')->comment('exact source payload, unmodified (PR-05); urlproceso keeps its nested shape (dictionary Decision 2)');
            $table->string('payload_sha256', 64)->comment('hash of canonical raw payload — computed before JSONB normalization');
            $table->timestampTz('retrieved_at');
            $table->timestampsTz();

            // Idempotency: unchanged rows re-fetched are no-ops; changed payloads append.
            $table->unique(
                ['dataset_id', 'source_row_id', 'payload_sha256'],
                'secop_raw_records_idempotency_unique'
            );
            // Non-unique: consumed by release construction (Migration 2) for state-change lookups.
            $table->index(['dataset_id', 'source_row_id']);
            // Deliberately absent: any index over payload contents (ADR-020 — raw
            // payloads are never a search surface).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secop_raw_records');
        Schema::dropIfExists('secop_ingestion_batches');
    }
};
