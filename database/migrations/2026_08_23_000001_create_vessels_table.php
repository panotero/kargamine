<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vessel master data - supersedes the vessel_voyages table's original
     * free-text vessel_name (see the docblock on
     * 2026_07_29_195201_create_vessel_voyages_table.php). status codes:
     * 1 Active, 2 Under Repair, 3 Out of Service, 4 Decommissioned.
     * "Last serviced" is derived from the latest vessel_maintenance_records
     * row rather than stored here, to avoid a duplicated/staleness-prone
     * column.
     */
    public function up(): void
    {
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vessel_code')->nullable()->unique();
            $table->string('vessel_type')->nullable();
            $table->string('imo_number')->nullable();
            $table->string('call_sign')->nullable();
            $table->string('mmsi_number')->nullable();
            $table->string('flag_state')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('engine_count')->nullable();
            $table->decimal('engine_power_hp', 10, 2)->nullable();
            $table->date('date_manufactured')->nullable();
            $table->decimal('gross_tonnage', 12, 2)->nullable();
            $table->decimal('deadweight_tonnage', 12, 2)->nullable();
            $table->unsignedInteger('capacity_teu')->nullable();
            $table->decimal('length_overall_m', 8, 2)->nullable();
            $table->decimal('beam_m', 8, 2)->nullable();
            $table->decimal('draft_m', 8, 2)->nullable();
            $table->decimal('max_speed_knots', 6, 2)->nullable();
            $table->foreignId('home_port_id')->nullable()->constrained('ports', 'port_id')->nullOnDelete();
            $table->string('owner_operator')->nullable();
            $table->string('classification_society')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
