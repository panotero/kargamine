<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_ancillary_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')
                ->constrained('client_masters')
                ->cascadeOnDelete();

            // Snapshot columns - set server-side from the parent client at
            // creation time, not client-submitted (see
            // ClientMasterController::saveStage4).
            $table->string('client_code')->nullable();
            $table->string('client_mnemonic')->nullable();
            $table->string('client_business_name')->nullable();

            $table->string('required_service')->nullable();
            $table->string('location')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('unit_rate_vat_ex', 15, 2)->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_ancillary_services');
    }
};
