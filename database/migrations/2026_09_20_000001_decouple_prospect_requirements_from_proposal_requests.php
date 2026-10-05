<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Prospect "Requirements" (Freight/Trucking/Charter, added from the
 * original Requirements tab) and "Proposal Requests" (the Request for
 * Proposal wizard's Draft/Under Review cards) are two independent
 * concepts - having Requirements doesn't mean a Proposal has been
 * requested. They used to share a row via proposal_request_id, which
 * meant deleting a draft Proposal Request destroyed the prospect's
 * Requirements too (and made the Requirements tab silently mint a
 * Proposal Request/code just by being used). This gives Requirements
 * their own direct, permanent owner (prospect_id) and severs the
 * proposal_request_id link entirely.
 */
return new class extends Migration
{
    private array $tables = [
        'proposal_request_containers',
        'proposal_request_truckings',
        'proposal_request_charters',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->foreignId('prospect_id')->nullable()->after('id')->constrained('prospects')->cascadeOnDelete();
            });

            // Portable across MySQL and the test suite's SQLite driver -
            // MySQL's UPDATE...JOIN syntax isn't supported by SQLite.
            DB::statement("
                UPDATE {$table}
                SET prospect_id = (
                    SELECT prospect_id FROM proposal_requests WHERE proposal_requests.id = {$table}.proposal_request_id
                )
                WHERE proposal_request_id IS NOT NULL
            ");

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                    $blueprint->dropForeign("{$table}_proposal_request_id_foreign");
                }
                $blueprint->dropColumn('proposal_request_id');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('proposal_request_id')->nullable()->after('id')->constrained('proposal_requests')->cascadeOnDelete();
            });

            Schema::table($table, function (Blueprint $blueprint) {
                if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                    $blueprint->dropForeign(["prospect_id"]);
                }
                $blueprint->dropColumn('prospect_id');
            });
        }
    }
};
