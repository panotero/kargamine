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
        Schema::create('client_finance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->unique()->constrained('client_masters')->cascadeOnDelete();

            $table->string('credit_terms')->nullable();  // LOV
            $table->string('payment_mode')->nullable();  // LOV
            $table->boolean('standard_billing_service')->default(false);

            // Invoice submission
            $table->enum('invoice_submission', ['electronic', 'courier'])->nullable();
            $table->string('invoice_email_address')->nullable();
            $table->string('invoice_courier_recipient')->nullable();
            $table->string('invoice_courier_contact')->nullable();
            $table->text('invoice_courier_address')->nullable();

            // Payment method
            $table->enum('payment_method', ['check_pickup', 'direct_remittance'])->nullable();
            $table->text('check_pickup_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();

            // Additional billing service requests
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
        Schema::dropIfExists('client_finance');
    }
};
