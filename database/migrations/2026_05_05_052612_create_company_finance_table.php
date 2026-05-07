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
        Schema::create('company_finance', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('company_info_master')
                ->cascadeOnDelete();

            $table->string('credit_terms')->nullable(); // LOV


            $table->string('payment_mode');

            $table->boolean('document_handling')->default(false);
            $table->boolean('billing_summary_report')->default(false);

            $table->text('other_requests')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_finance');
    }
};
