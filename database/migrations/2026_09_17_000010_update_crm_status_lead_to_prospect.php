<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Data-only migration: existing databases seeded before the Prospect rename
 * still have a crm_status row with status = 'LEAD'. Flip it in place to
 * 'PROSPECT' (same row/id, just renamed) so it matches CrmStatusSeeder going
 * forward. Guarded by the where clause so this is a safe no-op on a fresh
 * database (crm_status is empty until CrmStatusSeeder runs) and idempotent
 * on repeated runs.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('crm_status')->where('status', 'LEAD')->update(['status' => 'PROSPECT']);
    }

    public function down(): void
    {
        DB::table('crm_status')->where('status', 'PROSPECT')->update(['status' => 'LEAD']);
    }
};
