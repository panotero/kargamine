<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * RFP wizard "Terms & Conditions" ancillary fee grid - one row per
 * (product-type section, service) pair. `section` is one of the 5 T&C
 * product-type headings (Charter has no ancillary table, intentionally
 * excluded).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_ancillary_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();

            $table->string('section');
            $table->string('service_name');
            $table->decimal('fee_amount', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(['proposal_request_id', 'section', 'service_name'], 'pr_ancillary_fees_request_section_service_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_ancillary_fees');
    }
};
