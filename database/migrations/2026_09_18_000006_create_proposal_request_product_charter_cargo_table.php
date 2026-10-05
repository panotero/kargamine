<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_charter_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_product_charter_id')
                ->constrained('proposal_request_product_charters', 'id', 'prpc_cargo_charter_fk')
                ->cascadeOnDelete();

            $table->string('cargo_type')->nullable();
            $table->text('cargo_description')->nullable();
            $table->text('special_requirements')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_charter_cargo');
    }
};
