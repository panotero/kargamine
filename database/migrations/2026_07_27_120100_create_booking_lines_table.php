<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_lines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings', 'booking_id')
                ->cascadeOnDelete();

            // Mirrors client_contract_rates' FK shape - container_id/class/size are
            // derived from container_variant_id at write time, not trusted from
            // client input, to guarantee they can never disagree with the variant.
            $table->foreignId('container_id')->constrained('containers')->restrictOnDelete();
            $table->foreignId('container_class_id')->constrained('container_class')->restrictOnDelete();
            $table->foreignId('container_size_id')->constrained('container_size')->restrictOnDelete();
            $table->foreignId('container_variant_id')->constrained('container_variants')->restrictOnDelete();

            $table->unsignedInteger('quantity')->default(1);
            $table->string('description')->nullable();
            $table->decimal('weight_kg', 12, 2)->nullable();
            $table->decimal('volume_cbm', 12, 2)->nullable();
            $table->boolean('is_hazardous')->default(false);
            $table->boolean('is_fragile')->default(false);

            // Pricing snapshot - per line, since FRT now resolves per container variant.
            $table->decimal('frt_snapshot', 12, 2)->default(0);
            $table->string('discount_type_snapshot')->nullable(); // percentage | fixed
            $table->decimal('discount_value_snapshot', 12, 2)->default(0);
            $table->decimal('frt_after_discount_snapshot', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0); // frt_after_discount * quantity

            $table->timestamps();

            $table->index(['booking_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_lines');
    }
};
