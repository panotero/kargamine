<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "FINAL PORT" is purely a display convention (last row by sort_order) -
 * no stored is_final flag, same as the Requirements tab's charter ports.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_charter_ports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_product_charter_id')
                ->constrained('proposal_request_product_charters', 'id', 'prpc_port_charter_fk')
                ->cascadeOnDelete();
            $table->foreignId('port_id')
                ->nullable()
                ->constrained('ports', 'port_id', 'prpc_port_port_fk')
                ->nullOnDelete();

            $table->string('port_charge_account')->nullable(); // direct | invoice
            $table->decimal('port_charge_amount', 15, 2)->nullable();

            $table->integer('cargoes_for_loading')->nullable();
            $table->string('cargo_measurement_for_loading')->nullable();
            $table->string('revenue_ton_unit_loading')->nullable(); // CBM | MT

            $table->integer('cargoes_for_unloading')->nullable();
            $table->string('cargo_measurement_for_unloading')->nullable();
            $table->string('revenue_ton_unit_unloading')->nullable(); // CBM | MT

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_charter_ports');
    }
};
