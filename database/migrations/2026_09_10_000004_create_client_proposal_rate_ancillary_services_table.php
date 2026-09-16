<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Same shape as client_ancillary_services (ClientMaster's Stage 4) - just a
 * list of special charges, no rates/calculation - but scoped to one
 * container line (client_proposal_rates row) instead of a whole client.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_proposal_rate_ancillary_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_rate_id')->constrained('client_proposal_rates')->cascadeOnDelete();

            $table->string('required_service')->nullable();
            $table->string('location')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_proposal_rate_ancillary_services');
    }
};
