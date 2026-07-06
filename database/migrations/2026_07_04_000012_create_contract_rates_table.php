<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            // Same matching dimensions as proposals_rates, so a booking can
            // resolve the correct contract line the same way a proposal does.
            $table->string('route_from');
            $table->string('route_to');
            $table->integer('min_van_qty');
            $table->integer('container_class');
            $table->integer('container_type');
            $table->integer('container_size');
            $table->integer('origin_service_type');
            $table->integer('destination_service_type');

            // Discount applies ONLY to the FRT (tariff rate) portion of ART.
            // BSC, RA, GRI, port charges, handling, trucking and VAT are
            // never touched by a contract - they stay system-computed.
            $table->enum('discount_type', ['PERCENTAGE', 'FIXED']);
            $table->decimal('discount_value', 12, 2); // 0-100 if PERCENTAGE, peso amount if FIXED

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique([
                'contract_id',
                'route_from',
                'route_to',
                'container_class',
                'container_type',
                'container_size',
                'origin_service_type',
                'destination_service_type',
            ], 'contract_rates_unique_line');

            $table->index(['route_from', 'route_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_rates');
    }
};
