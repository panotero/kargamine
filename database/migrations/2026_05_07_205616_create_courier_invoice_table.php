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
        Schema::create('courier_invoice', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('company_info_master')
                ->cascadeOnDelete();
            $table->text('invoice_contact')->nullable();
            $table->text('invoice_contact_number')->nullable();
            $table->text('invoice_courier_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_invoice');
    }
};
