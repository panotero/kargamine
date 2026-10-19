<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces proposal_requests.terms_of_payment_standard/_charter (one value
 * shared across every product) with one row per Terms & Conditions
 * section - each of the 6 per-product reference sections on that tab now
 * has its own Terms of Payment dropdown. Same shape/convention as the
 * existing proposal_request_ancillary_fees table (keyed by `section`).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_terms_of_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();
            $table->string('section');
            $table->string('terms_of_payment');
            $table->timestamps();

            $table->unique(['proposal_request_id', 'section'], 'pr_terms_of_payment_unique');
        });

        Schema::table('proposal_requests', function (Blueprint $table) {
            $table->dropColumn(['terms_of_payment_standard', 'terms_of_payment_charter']);
        });
    }

    public function down(): void
    {
        Schema::table('proposal_requests', function (Blueprint $table) {
            $table->string('terms_of_payment_standard')->nullable();
            $table->string('terms_of_payment_charter')->nullable();
        });

        Schema::dropIfExists('proposal_request_terms_of_payment');
    }
};
