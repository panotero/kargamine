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
        Schema::create('client_contract_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('client_contracts')->cascadeOnDelete();

            $table->foreignId('origin_port_id')->constrained('ports', 'port_id')->restrictOnDelete();
            $table->foreignId('destination_port_id')->constrained('ports', 'port_id')->restrictOnDelete();
            $table->foreignId('container_id')->constrained('containers')->restrictOnDelete();
            $table->foreignId('container_class_id')->constrained('container_class')->restrictOnDelete();
            $table->foreignId('container_size_id')->constrained('container_size')->restrictOnDelete();
            $table->foreignId('container_variant_id')->constrained('container_variants')->restrictOnDelete();

            $table->decimal('base_rate', 12, 2)->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('final_rate', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_contract_rates');
    }
};
