<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Top Load Cargo - Rolling Cargo rows only (Loose Cargo doesn't get this
 * section per the brief).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_topload_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_product_rolling_cargo_id')
                ->constrained('proposal_request_product_rolling_cargo', 'id', 'prp_topload_rolling_fk')
                ->cascadeOnDelete();

            $table->string('top_load_type')->nullable();
            $table->text('details')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('units')->nullable();
            $table->decimal('revenue_ton', 12, 2)->nullable();
            $table->string('revenue_ton_unit')->nullable(); // CBM | MT
            $table->string('measurement')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_topload_cargo');
    }
};
