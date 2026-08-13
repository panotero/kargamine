<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Serviceable areas move from being owned by a single Port to being
     * owned by a Location (matching how the Rate Maintenance spreadsheet
     * actually organizes them - a serviceable area is a trucking zone
     * within a province/location, not tied to one specific pier). Managed
     * from the Location modal now, alongside that location's ports.
     *
     * Existing serviceable_areas/trucking_tariffs rows are prototype seed
     * data with no downstream bookings referencing them yet (see
     * PortSeeder/ServiceableAreaSeeder, which reseed for real from the
     * Rate Maintenance spreadsheet), so this wipes rather than backfilling
     * a location for old port-scoped rows.
     *
     * SQLite can't drop a named foreign key in place (Blueprint::dropForeign
     * throws there - see Laravel's SQLite grammar), so the table is dropped
     * and recreated for that driver instead of altered in place.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=OFF')
            : DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('trucking_tariffs')->truncate();
        DB::table('serviceable_areas')->truncate();

        if ($driver === 'sqlite') {
            Schema::drop('serviceable_areas');

            Schema::create('serviceable_areas', function (Blueprint $table) {
                $table->id('area_id');
                $table->foreignId('location_id')
                    ->constrained('locations', 'location_id')
                    ->restrictOnDelete();
                $table->string('area_name', 150);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['location_id', 'area_name']);
            });
        } else {
            Schema::table('serviceable_areas', function (Blueprint $table) {
                $table->dropForeign(['port_id']);
                $table->dropUnique(['port_id', 'area_name']);
                $table->dropColumn('port_id');

                $table->foreignId('location_id')->after('area_id')
                    ->constrained('locations', 'location_id')
                    ->restrictOnDelete();
                $table->unique(['location_id', 'area_name']);
            });
        }

        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=ON')
            : DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=OFF')
            : DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('trucking_tariffs')->truncate();
        DB::table('serviceable_areas')->truncate();

        if ($driver === 'sqlite') {
            Schema::drop('serviceable_areas');

            Schema::create('serviceable_areas', function (Blueprint $table) {
                $table->id('area_id');
                $table->foreignId('port_id')
                    ->constrained('ports', 'port_id')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
                $table->string('area_name', 150);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['port_id', 'area_name']);
            });
        } else {
            Schema::table('serviceable_areas', function (Blueprint $table) {
                $table->dropForeign(['location_id']);
                $table->dropUnique(['location_id', 'area_name']);
                $table->dropColumn('location_id');

                $table->foreignId('port_id')->after('area_id')
                    ->constrained('ports', 'port_id')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
                $table->unique(['port_id', 'area_name']);
            });
        }

        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=ON')
            : DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
