<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Step 10 (Voyage Plan). One row per LEG, not per vessel - the
     * SOP's own example data ("Lady Callista 84-A/B/C/D") is one vessel's
     * rotation split into 4 legs, each with its own ports/dates. No
     * separate Vessel model: the SOP lists no vessel-level attributes
     * beyond the name, so vessel_name stays a plain string here rather
     * than an unused normalization.
     */
    public function up(): void
    {
        Schema::create('vessel_voyages', function (Blueprint $table) {
            $table->id();

            $table->string('vessel_name');
            $table->string('voyage_mnemonic')->unique();
            $table->string('voyage_leg');

            $table->foreignId('origin_port_id')->constrained('ports', 'port_id')->restrictOnDelete();
            $table->foreignId('destination_port_id')->constrained('ports', 'port_id')->restrictOnDelete();

            $table->timestamp('estimated_departure_at')->nullable();
            $table->timestamp('estimated_arrival_at')->nullable();

            $table->timestamps();

            $table->index(['vessel_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessel_voyages');
    }
};
