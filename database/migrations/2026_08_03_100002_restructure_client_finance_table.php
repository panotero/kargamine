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
        Schema::table('client_finance', function (Blueprint $table) {
            $table->dropColumn([
                'payment_mode',
                'standard_billing_service',
                'invoice_submission',
                'invoice_email_address',
                'invoice_courier_recipient',
                'invoice_courier_contact',
                'invoice_courier_address',
                'payment_method',
                'check_pickup_address',
                'bank_name',
                'bank_account_number',
                'document_handling',
                'billing_summary_report',
                'other_requests',
            ]);
        });

        Schema::table('client_finance', function (Blueprint $table) {
            $table->string('client_business_name')->nullable()->after('client_id');
            $table->string('tin_number')->nullable();
            $table->text('tin_registered_address')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('withholding_tax_code')->nullable();
            $table->string('trade_name')->nullable();
            $table->date('tin_registration_date')->nullable();
            $table->string('line_of_business')->nullable();
            $table->decimal('tax_percent', 5, 2)->nullable();
            $table->decimal('withholding_tax_percent', 5, 2)->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->foreignId('cro_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('max_declared_value', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_finance', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cro_user_id');
            $table->dropColumn([
                'client_business_name',
                'tin_number',
                'tin_registered_address',
                'tax_status',
                'withholding_tax_code',
                'trade_name',
                'tin_registration_date',
                'line_of_business',
                'tax_percent',
                'withholding_tax_percent',
                'mode_of_payment',
                'max_declared_value',
            ]);
        });

        Schema::table('client_finance', function (Blueprint $table) {
            $table->string('payment_mode')->nullable();
            $table->boolean('standard_billing_service')->default(false);
            $table->enum('invoice_submission', ['electronic', 'courier'])->nullable();
            $table->string('invoice_email_address')->nullable();
            $table->string('invoice_courier_recipient')->nullable();
            $table->string('invoice_courier_contact')->nullable();
            $table->text('invoice_courier_address')->nullable();
            $table->enum('payment_method', ['check_pickup', 'direct_remittance'])->nullable();
            $table->text('check_pickup_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->boolean('document_handling')->default(false);
            $table->boolean('billing_summary_report')->default(false);
            $table->text('other_requests')->nullable();
        });
    }
};
