<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 *  ⚠️  PENDING INFRASTRUCTURE — THIS TEST DOES NOT VALIDATE ANYTHING YET.  ⚠️
 * ═══════════════════════════════════════════════════════════════════════════
 *
 *  Append-only enforcement for the raw layer is PRIVILEGE-BASED: the web/
 *  application database role must hold NO UPDATE/DELETE grants on
 *  secop_raw_records and secop_ingestion_batches (Migration Proposal 1,
 *  "Append-only enforcement approach", defense layer 1).
 *
 *  Those DB roles ARE NOT CONFIGURED YET. Therefore there is nothing here to
 *  validate: this file is a SKIPPED PLACEHOLDER whose only job is to remain
 *  visible in the suite until the roles exist.
 *
 *  The presence of this test is NOT proof that append-only enforcement has
 *  been validated. Current enforcement relies on application discipline only
 *  (defense layer 2).
 *
 *  WHEN THE DB ROLES ARE CONFIGURED, replace the skip with real assertions:
 *    1. Connecting as the application role, an UPDATE or DELETE on
 *       secop_raw_records / secop_ingestion_batches must fail with a
 *       permission-denied error.
 *    2. The ingestion/audit role retains INSERT (and SELECT) capability.
 *    3. Update the comment above and remove @group pending-db-roles.
 *
 * @group pending-db-roles
 */
class RawRecordAppendOnlyTest extends TestCase
{
    public function test_append_only_enforcement_is_not_yet_validated_pending_db_role_configuration(): void
    {
        $this->markTestSkipped(
            'Append-only enforcement is privilege-based (Migration Proposal 1, '
            .'defense layer 1) and the DB roles are not configured yet. This '
            .'placeholder exists so the unvalidated state stays visible in the '
            .'suite. Replace this skip with privilege assertions once the '
            .'application/ingestion DB roles are configured.'
        );
    }
}
