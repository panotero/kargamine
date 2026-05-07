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
        Schema::create('e_invoice', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('company_info_master')
                ->cascadeOnDelete();
            $table->string('invoice_email_address');

            $table->string('invoice_email_cc_address')->nullable();
            $table->string('invoice_email_bcc_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_invoice');
    }
};
