<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Optional pickup/drop-off area per container line - reuses the existing
 * ServiceableArea catalog (scoped to Location, see
 * 2026_08_13_033349_scope_serviceable_areas_to_location.php) rather than
 * inventing a new port-level concept.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_proposal_rates', function (Blueprint $table) {
            $table->foreignId('origin_pickup_area_id')->nullable()->after('origin_port_id')
                ->constrained('serviceable_areas', 'area_id')->nullOnDelete();
            $table->foreignId('destination_pickup_area_id')->nullable()->after('destination_port_id')
                ->constrained('serviceable_areas', 'area_id')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('client_proposal_rates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('origin_pickup_area_id');
            $table->dropConstrainedForeignId('destination_pickup_area_id');
        });
    }
};
