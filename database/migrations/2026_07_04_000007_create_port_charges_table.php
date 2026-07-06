<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('port_charges', function (Blueprint $table) {
            $table->id('port_charge_id');
            $table->foreignId('port_id')
                ->constrained('ports', 'port_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('charge_type_id')
                ->constrained('charge_types', 'charge_type_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['port_id', 'charge_type_id', 'effective_date'], 'port_charges_unique');
            $table->index(['port_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('port_charges');
    }
};
