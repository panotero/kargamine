<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Narrow slice of the (unbuilt) Pier & Port Handling plan's Phase 1 -
     * just enough to let a booking claim a specific ContainerAsset per
     * cargo unit. Loading/discharge status transitions belong to that
     * later module; here every unit just sits at Pending.
     */
    public function up(): void
    {
        Schema::create('booking_container_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_line_id')
                ->constrained('booking_lines')
                ->cascadeOnDelete();

            $table->foreignId('booking_id')
                ->constrained('bookings', 'booking_id')
                ->cascadeOnDelete();

            $table->unsignedInteger('unit_index'); // 1-based, within its line ("2 of 3")
            $table->string('gate_pass_code')->unique(); // sequential across the whole booking

            $table->foreignId('container_asset_id')
                ->nullable()
                ->constrained('container_assets')
                ->nullOnDelete();

            $table->string('seal_no')->nullable();

            $table->unsignedTinyInteger('status')->default(1);
            // 1 pending, 2 loaded, 3 discharged, 4 exception - only "pending" is
            // reachable until the Pier & Port Handling module exists.

            $table->foreignId('origin_port_id')->constrained('ports', 'port_id')->restrictOnDelete();
            $table->foreignId('destination_port_id')->constrained('ports', 'port_id')->restrictOnDelete();

            $table->timestamps();

            $table->index(['booking_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_container_units');
    }
};
