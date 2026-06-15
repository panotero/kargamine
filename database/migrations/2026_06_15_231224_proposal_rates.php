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
        //
        Schema::create('proposals_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')
                ->constrained('proposals')->cascadeOnDelete();
            $table->integer('proposed_rate');
            $table->string('route_from');
            $table->string('route_to');
            $table->integer('min_van_qty');
            $table->string('van_type');
            $table->integer('origin_service_type');
            $table->integer('destination_service_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('proposals_rates');
    }
};
