<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Validates the raw-record idempotency contract: the unique index
 * (dataset_id, source_row_id, payload_sha256) — Migration Proposal 1.
 *
 * Fixtures are verified SODA samples (tests/Fixtures/secop/) — no invented
 * data is presented as source data (PR-06).
 */
class RawRecordIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    private function fixturePayload(): array
    {
        $raw = file_get_contents(
            base_path('tests/Fixtures/secop/jbjy-vk9h.sample.json')
        );

        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Canonicalization mirrors the storage baseline §8 rule 2: recursively
     * sorted keys, exact source values, then sha256. (PHP's json_encode has
     * no sort flag, so sorting is explicit.)
     */
    private function canonicalize(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        $value = array_map([$this, 'canonicalize'], $value);

        if (! array_is_list($value)) {
            ksort($value, SORT_STRING);
        }

        return $value;
    }

    private function canonicalHash(array $payload): string
    {
        $canonical = json_encode(
            $this->canonicalize($payload),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
        );

        return hash('sha256', $canonical);
    }

    private function insertBatch(array $overrides = []): int
    {
        return DB::table('secop_ingestion_batches')->insertGetId(array_merge([
            'dataset_id' => 'jbjy-vk9h',
            'query_signature' => '$limit=1',
            'rows_fetched' => 1,
            'rows_stored' => 1,
            'status' => 'completed',
        ], $overrides));
    }

    private function insertRawRecord(
        int $batchId,
        array $payload,
        ?string $sourceRowId,
        ?string $hash = null
    ): void {
        DB::table('secop_raw_records')->insert([
            'batch_id' => $batchId,
            'dataset_id' => 'jbjy-vk9h',
            'source_row_id' => $sourceRowId,
            'payload' => json_encode(
                $payload,
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
            ),
            'payload_sha256' => $hash ?? $this->canonicalHash($payload),
            'retrieved_at' => now(),
        ]);
    }

    public function test_exact_duplicate_insert_is_rejected(): void
    {
        $batchId = $this->insertBatch();
        $payload = $this->fixturePayload();
        $hash = $this->canonicalHash($payload);

        $this->insertRawRecord($batchId, $payload, 'row-fixture-1', $hash);

        try {
            // Identical insert: must violate secop_raw_records_idempotency_unique.
            // Savepoint isolates the expected failure from the surrounding
            // RefreshDatabase transaction (PostgreSQL aborts on error).
            DB::beginTransaction();
            $this->insertRawRecord($batchId, $payload, 'row-fixture-1', $hash);
            $this->fail(
                'Expected unique violation on secop_raw_records_idempotency_unique'
            );
        } catch (QueryException $e) {
            $this->assertStringContainsString(
                'secop_raw_records_idempotency_unique',
                $e->getMessage()
            );
        } finally {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        }

        $this->assertSame(
            1,
            DB::table('secop_raw_records')
                ->where('source_row_id', 'row-fixture-1')
                ->count()
        );
    }

    public function test_changed_payload_creates_state_history(): void
    {
        $batchId = $this->insertBatch();
        $payloadA = $this->fixturePayload();
        $this->insertRawRecord($batchId, $payloadA, 'row-fixture-1');

        // State change simulation: a TEST-ONLY mutation of one field of a real
        // fixture payload. This is a schema-contract test, not data — the
        // modified value is never presented as source data anywhere.
        $payloadB = $payloadA;
        $payloadB['estado_contrato'] = 'Liquidado';
        $this->insertRawRecord($batchId, $payloadB, 'row-fixture-1');

        // Both rows coexist: raw level preserves state history (PR-05).
        $this->assertSame(
            2,
            DB::table('secop_raw_records')
                ->where('dataset_id', 'jbjy-vk9h')
                ->where('source_row_id', 'row-fixture-1')
                ->count()
        );
    }

    public function test_null_source_row_id_is_schema_capable(): void
    {
        // Schema-level confirmation of the defensive-safeguard decision:
        // a null source_row_id CAN be stored. Ingestion code (implementation
        // phase) must treat it as an anomaly requiring review — behavioral
        // tests arrive with that code.
        $batchId = $this->insertBatch();
        $payload = $this->fixturePayload();
        $this->insertRawRecord($batchId, $payload, null);

        $this->assertSame(
            1,
            DB::table('secop_raw_records')
                ->whereNull('source_row_id')
                ->count()
        );
    }
}
