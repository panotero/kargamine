<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SQLite (used by the test suite) can't drop a foreign key constraint
     * at all - Laravel's grammar refuses even with doctrine/dbal installed
     * - so dropForeign() is skipped there; dropColumn() alone still removes
     * the column correctly (SQLite recreates the table under the hood).
     */
    public function up(): void
    {
        Schema::table('containers', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['container_type_id']);
            }

            $table->dropColumn('container_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->foreignId('container_type_id')
                ->constrained('container_type')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};
