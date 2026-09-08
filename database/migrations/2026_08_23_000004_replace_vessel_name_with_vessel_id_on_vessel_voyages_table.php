<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Supersedes the "no separate Vessel model" decision documented on
     * 2026_07_29_195201_create_vessel_voyages_table.php now that Vessel
     * Management exists. Safe to drop-and-replace outright (no backfill)
     * because vessel_voyages has zero rows in every environment this has
     * shipped to.
     */
    public function up(): void
    {
        Schema::table('vessel_voyages', function (Blueprint $table) {
            $table->dropColumn('vessel_name');
            $table->foreignId('vessel_id')->nullable()->after('id')->constrained('vessels')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vessel_voyages', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['vessel_id']);
            }

            $table->dropColumn('vessel_id');
            $table->string('vessel_name')->after('id');
        });
    }
};
