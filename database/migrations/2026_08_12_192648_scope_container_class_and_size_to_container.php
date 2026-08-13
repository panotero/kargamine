<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Container Class/Size move from global lookup tables to being owned by
     * a single Container (each container manages its own class + size
     * lists from the Container modal now, not shared app-wide lookups).
     * container_variants.container_class_id also becomes nullable so a
     * container can have a "base" size-only variant (no class) alongside
     * its class-specific ones - matching how container SKUs are actually
     * coded ("CV - 20-GP" vs "CV - 20-GP - A").
     *
     * Existing container_class/container_size/container_variants rows (and
     * the lane tariff prices tied to those variants) are prototype seed
     * data with no downstream bookings/proposals/contracts referencing
     * them yet, so this wipes and lets the app reseed under the new shape
     * rather than trying to backfill an owning container for old rows.
     */
    public function up(): void
    {
        Schema::table('container_variants', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable()->change();
        });

        $driver = Schema::getConnection()->getDriverName();
        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=OFF')
            : DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('lane_tariff_rate_prices')->truncate();
        DB::table('container_variants')->truncate();
        DB::table('container_class')->truncate();
        DB::table('container_size')->truncate();

        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=ON')
            : DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Schema::table('container_class', function (Blueprint $table) {
            $table->foreignId('container_id')->after('id')
                ->constrained('containers')->cascadeOnDelete();
            $table->unique(['container_id', 'class']);
        });

        Schema::table('container_size', function (Blueprint $table) {
            $table->foreignId('container_id')->after('id')
                ->constrained('containers')->cascadeOnDelete();
            $table->unique(['container_id', 'size']);
        });
    }

    public function down(): void
    {
        Schema::table('container_size', function (Blueprint $table) {
            $table->dropUnique(['container_id', 'size']);
            $table->dropConstrainedForeignId('container_id');
        });

        Schema::table('container_class', function (Blueprint $table) {
            $table->dropUnique(['container_id', 'class']);
            $table->dropConstrainedForeignId('container_id');
        });

        Schema::table('container_variants', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable(false)->change();
        });
    }
};
