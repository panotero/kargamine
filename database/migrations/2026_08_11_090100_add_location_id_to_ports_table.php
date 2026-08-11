<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->after('port_id')
                ->constrained('locations', 'location_id');
        });

        // Backfill: every pre-existing port becomes its own single-port
        // location (named after the port), so nothing is left orphaned
        // once location_id is made required below. Re-group ports under
        // shared locations afterwards via the Locations settings UI.
        DB::table('ports')->orderBy('port_id')->get()->each(function ($port) {
            $locationId = DB::table('locations')->insertGetId([
                'name' => $port->name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'location_id');

            DB::table('ports')->where('port_id', $port->port_id)->update(['location_id' => $locationId]);
        });

        Schema::table('ports', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable(false)->change();
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }

    public function down(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->after('port_id');
        });

        Schema::table('ports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
        });
    }
};
