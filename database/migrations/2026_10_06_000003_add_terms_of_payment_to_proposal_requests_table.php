<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * RFP wizard Terms & Conditions tab - separate Terms of Payment strings for
 * the standard product set vs. Charter.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_requests', function (Blueprint $table) {
            $table->string('terms_of_payment_standard')->nullable();
            $table->string('terms_of_payment_charter')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('proposal_requests', function (Blueprint $table) {
            $table->dropColumn(['terms_of_payment_standard', 'terms_of_payment_charter']);
        });
    }
};
