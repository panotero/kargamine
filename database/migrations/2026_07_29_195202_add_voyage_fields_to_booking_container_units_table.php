<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Step 11 (Generation of Loadlist). Per-container-unit, matching
     * the granularity already used for CV Assignment/EIR/Gate Pass - a
     * booking's containers can ride different voyage legs. shut_out_at
     * is set when CSR tags a unit as having missed its vessel's actual
     * cutoff (from either "In Yard" or "For Vessel Loading" per the SOP);
     * re-assigning a voyage afterward clears it.
     */
    public function up(): void
    {
        Schema::table('booking_container_units', function (Blueprint $table) {
            $table->foreignId('vessel_voyage_id')->nullable()->after('gate_in_scanned_by')
                ->constrained('vessel_voyages')->nullOnDelete();
            $table->decimal('equivalent_teu', 8, 2)->nullable()->after('vessel_voyage_id');
            $table->foreignId('relay_port_id')->nullable()->after('equivalent_teu')
                ->constrained('ports', 'port_id')->nullOnDelete();
            $table->timestamp('shut_out_at')->nullable()->after('relay_port_id');
        });
    }

    public function down(): void
    {
        Schema::table('booking_container_units', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['vessel_voyage_id']);
                $table->dropForeign(['relay_port_id']);
            }

            $table->dropColumn(['vessel_voyage_id', 'equivalent_teu', 'relay_port_id', 'shut_out_at']);
        });
    }
};
