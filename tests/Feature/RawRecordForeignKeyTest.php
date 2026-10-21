<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Validates the raw-layer provenance chain: every raw record belongs to an
 * existing ingestion batch, and a batch cannot be deleted while its records
 * exist (restrictOnDelete — the structural append-only reinforcement).
 */
class RawRecordForeignKeyTest extends TestCase
{
    use RefreshDatabase;

    private function fixturePayload(): array
    {
        $raw = file_get_contents(
            base_path('tests/Fixtures/secop/jbjy-vk9h.sample.json')
        );

        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_raw_record_requires_existing_batch(): void
    {
        try {
            DB::table('secop_raw_records')->insert([
                'batch_id' => 999999, // nonexistent batch
                'dataset_id' => 'jbjy-vk9h',
                'source_row_id' => 'row-fixture-1',
                'payload' => json_encode($this->fixturePayload(), JSON_THROW_ON_ERROR),
                'payload_sha256' => str_repeat('a', 64),
                'retrieved_at' => now(),
            ]);
            $this->fail('Expected FK violation for a nonexistent batch_id');
        } catch (QueryException $e) {
            $this->assertStringContainsString(
                'secop_raw_records_batch_id_foreign',
                $e->getMessage()
            );
        }
    }

    public function test_batch_cannot_be_deleted_while_records_exist(): void
    {
        $batchId = DB::table('secop_ingestion_batches')->insertGetId([
            'dataset_id' => 'jbjy-vk9h',
            'query_signature' => '$limit=1',
            'rows_fetched' => 1,
            'rows_stored' => 1,
            'status' => 'completed',
        ]);

        DB::table('secop_raw_records')->insert([
            'batch_id' => $batchId,
            'dataset_id' => 'jbjy-vk9h',
            'source_row_id' => 'row-fixture-1',
            'payload' => json_encode($this->fixturePayload(), JSON_THROW_ON_ERROR),
            'payload_sha256' => str_repeat('b', 64),
            'retrieved_at' => now(),
        ]);

        try {
            // Savepoint: the expected DELETE failure aborts PostgreSQL's current
            // transaction; isolating it keeps the surrounding RefreshDatabase
            // transaction usable for the assertion that follows.
            DB::beginTransaction();
            DB::table('secop_ingestion_batches')
                ->where('id', $batchId)
                ->delete();
            $this->fail(
                'Expected restrict violation: a batch with raw records must not be deletable'
            );
        } catch (QueryException $e) {
            $this->assertStringContainsString(
                'secop_raw_records_batch_id_foreign',
                $e->getMessage()
            );
        } finally {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        }

        // The batch survives: provenance is durable.
        $this->assertTrue(
            DB::table('secop_ingestion_batches')->where('id', $batchId)->exists()
        );
    }
}
