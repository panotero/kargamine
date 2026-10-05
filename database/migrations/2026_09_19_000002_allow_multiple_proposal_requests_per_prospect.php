<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A prospect can now have multiple Proposal Requests over time (Draft ->
 * Under Review lifecycle, started fresh each time "Request for Proposal"
 * is clicked) instead of exactly one. Dropping the unique constraint on
 * prospect_id turns Prospect::proposalRequest() from a plain hasOne into a
 * latestOfMany()-scoped "latest draft" accessor (see Prospect.php) -
 * every existing call site that reads it keeps working unchanged.
 *
 * Guarded with existence checks throughout: MySQL DDL isn't transactional,
 * and getting the drop-index-that-backs-an-FK ordering right took a couple
 * of tries on the dev database, so this must stay safely re-runnable.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('proposal_requests', 'status')) {
            Schema::table('proposal_requests', function (Blueprint $table) {
                $table->string('status')->default('draft')->after('prospect_id');
            });
        }

        // MySQL won't drop an index the FK constraint still depends on for
        // its required index - make sure the replacement plain index
        // exists BEFORE dropping the unique one.
        if (! $this->indexExists('proposal_requests', 'proposal_requests_prospect_id_index')) {
            Schema::table('proposal_requests', function (Blueprint $table) {
                $table->index('prospect_id', 'proposal_requests_prospect_id_index');
            });
        }

        if ($this->indexExists('proposal_requests', 'proposal_requests_prospect_id_unique')) {
            Schema::table('proposal_requests', function (Blueprint $table) {
                $table->dropUnique('proposal_requests_prospect_id_unique');
            });
        }
    }

    public function down(): void
    {
        if (! $this->indexExists('proposal_requests', 'proposal_requests_prospect_id_unique')) {
            Schema::table('proposal_requests', function (Blueprint $table) {
                $table->unique('prospect_id', 'proposal_requests_prospect_id_unique');
            });
        }

        if ($this->indexExists('proposal_requests', 'proposal_requests_prospect_id_index')) {
            Schema::table('proposal_requests', function (Blueprint $table) {
                $table->dropIndex('proposal_requests_prospect_id_index');
            });
        }

        if (Schema::hasColumn('proposal_requests', 'status')) {
            Schema::table('proposal_requests', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return array_key_exists($indexName, Schema::getIndexes($table))
            || collect(Schema::getIndexes($table))->pluck('name')->contains($indexName);
    }
};
