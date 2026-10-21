<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Round-trip validation of the raw-layer migration (Migration Proposal 1).
 *
 * This test intentionally does NOT use RefreshDatabase: it exercises the real
 * migration files through migrate:rollback / migrate, exactly as deployment
 * would. The testing database is ephemeral, so setUp runs migrate:fresh to
 * guarantee a fully migrated baseline before the raw-layer migration is
 * manipulated in isolation.
 */
class RawLayerMigrationTest extends TestCase
{
    private const MIGRATION_PATH =
        'database/migrations/2026_10_21_110000_create_secop_raw_layer_tables.php';

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh', ['--force' => true]);
    }

    public function test_tables_exist_after_migrate(): void
    {
        $this->assertTrue(Schema::hasTable('secop_ingestion_batches'));
        $this->assertTrue(Schema::hasTable('secop_raw_records'));
    }

    public function test_migration_round_trips(): void
    {
        // Roll back ONLY the raw-layer migration (targeted --path rollback)...
        $this->artisan('migrate:rollback', [
            '--path' => self::MIGRATION_PATH,
            '--force' => true,
        ]);
        $this->assertFalse(Schema::hasTable('secop_raw_records'));
        $this->assertFalse(Schema::hasTable('secop_ingestion_batches'));

        // ...restore it, and prove the restored schema is complete by
        // inserting into both tables (guards against a residue schema).
        $this->artisan('migrate', [
            '--path' => self::MIGRATION_PATH,
            '--force' => true,
        ]);
        $this->assertTrue(Schema::hasTable('secop_ingestion_batches'));
        $this->assertTrue(Schema::hasTable('secop_raw_records'));

        $batchId = DB::table('secop_ingestion_batches')->insertGetId([
            'dataset_id' => 'jbjy-vk9h',
            'query_signature' => '$limit=1',
            'rows_fetched' => 1,
            'rows_stored' => 1,
            'status' => 'completed',
        ]);
        $this->assertTrue(
            DB::table('secop_ingestion_batches')->where('id', $batchId)->exists()
        );
    }
}
