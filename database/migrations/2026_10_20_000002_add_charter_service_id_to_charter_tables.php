<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Optional link from each charter row (Requirements tab's
 * proposal_request_charters and Products tab's
 * proposal_request_product_charters) to the new charter_services catalog.
 * Nullable/additive only - existing rows are unaffected.
 */
return new class extends Migration
{
    private array $tables = [
        'proposal_request_charters',
        'proposal_request_product_charters',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('charter_service_id')->nullable()->after('id');
                $blueprint->foreign('charter_service_id')
                    ->references('charter_service_id')->on('charter_services')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                // SQLite (used by the test suite) can't drop a foreign key
                // constraint directly - dropForeign() is skipped there;
                // dropColumn() alone still removes the column correctly
                // (SQLite recreates the table under the hood), matching the
                // pattern used elsewhere in this codebase (see
                // 2026_07_09_000939_drop_column_from_container_table.php).
                if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                    $blueprint->dropForeign(['charter_service_id']);
                }
                $blueprint->dropColumn('charter_service_id');
            });
        }
    }
};
