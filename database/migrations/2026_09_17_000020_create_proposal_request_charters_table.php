<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Third "product" - chartering the whole vessel. One row per charter
 * booking (repeatable, like a container row); each booking owns its own
 * cargo list and port-call list (see the two tables created after this
 * one), not shared across bookings.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_charters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();

            $table->date('charter_start_date')->nullable();
            $table->date('charter_end_date')->nullable();
            $table->decimal('declared_value', 15, 2)->nullable();
            $table->decimal('weight', 12, 2)->nullable();
            $table->string('weight_unit', 10)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_charters');
    }
};
