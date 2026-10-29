<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Trucking tariffs are really "cost to move between this serviceable area
 * and this port" - usable on either the origin or destination leg - not a
 * per delivery-type (DD/DP/PD/PP) catalog entry. The old (area_id,
 * delivery_type_id) key forced re-entering the same amount under 2-3
 * duplicate rows per area. Existing data is sparse placeholder seed data
 * (no port to backfill from), so this is a clean-slate rekey rather than a
 * backfill - approved by the user rather than preserving the old rows.
 *
 * SQLite can't drop a named foreign key in place (Blueprint::dropForeign
 * throws there - see Laravel's SQLite grammar, and
 * 2026_08_13_033349_scope_serviceable_areas_to_location.php for the same
 * pattern used previously), so the table is dropped and recreated for that
 * driver instead of altered in place.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=OFF')
            : DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('trucking_tariffs')->truncate();

        if ($driver === 'sqlite') {
            Schema::drop('trucking_tariffs');

            Schema::create('trucking_tariffs', function (Blueprint $table) {
                $table->id('trucking_tariff_id');
                $table->foreignId('area_id')
                    ->constrained('serviceable_areas', 'area_id')
                    ->restrictOnDelete();
                $table->foreignId('port_id')
                    ->constrained('ports', 'port_id')
                    ->restrictOnDelete();
                $table->decimal('amount', 12, 2)->default(0);
                $table->date('effective_date');
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['area_id', 'port_id', 'effective_date'], 'trucking_tariffs_area_port_unique');
                $table->index(['area_id', 'is_active']);
            });
        } else {
            Schema::table('trucking_tariffs', function (Blueprint $table) {
                $table->dropUnique('trucking_tariffs_unique');
                $table->dropForeign(['delivery_type_id']);
                $table->dropColumn('delivery_type_id');

                $table->foreignId('port_id')
                    ->after('area_id')
                    ->constrained('ports', 'port_id')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->unique(['area_id', 'port_id', 'effective_date'], 'trucking_tariffs_area_port_unique');
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

        if ($driver === 'sqlite') {
            Schema::drop('trucking_tariffs');

            Schema::create('trucking_tariffs', function (Blueprint $table) {
                $table->id('trucking_tariff_id');
                $table->foreignId('area_id')
                    ->constrained('serviceable_areas', 'area_id')
                    ->restrictOnDelete();
                $table->foreignId('delivery_type_id')
                    ->constrained('delivery_types', 'delivery_type_id')
                    ->restrictOnDelete();
                $table->decimal('amount', 12, 2)->default(0);
                $table->date('effective_date');
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['area_id', 'delivery_type_id', 'effective_date'], 'trucking_tariffs_unique');
                $table->index(['area_id', 'is_active']);
            });
        } else {
            Schema::table('trucking_tariffs', function (Blueprint $table) {
                $table->dropUnique('trucking_tariffs_area_port_unique');
                $table->dropForeign(['port_id']);
                $table->dropColumn('port_id');

                $table->foreignId('delivery_type_id')
                    ->after('area_id')
                    ->constrained('delivery_types', 'delivery_type_id')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->unique(['area_id', 'delivery_type_id', 'effective_date'], 'trucking_tariffs_unique');
            });
        }

        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys=ON')
            : DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
