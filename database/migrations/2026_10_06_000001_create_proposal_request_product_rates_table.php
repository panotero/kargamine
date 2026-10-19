<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * RFP wizard "Rate Matrix" - one row per priced product line
 * (container/rolling_cargo/loose_cargo/trucking/charter) within a Proposal
 * Request. product_id is a plain unsignedBigInteger (no FK), same
 * traceability-pointer-only convention as client_proposal_signed_rates: it
 * points into whichever product_* table product_type names (e.g.
 * proposal_request_product_containers), and must survive that source row
 * being edited/deleted without breaking this snapshot row.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();

            $table->string('product_type');
            $table->unsignedBigInteger('product_id');
            $table->string('item_no');

            $table->decimal('base_rate', 12, 2)->default(0);
            $table->decimal('adjustment', 12, 2)->default(0);
            $table->decimal('total_rate', 12, 2)->default(0);

            // [{detail, base, adjustment}, ...]
            $table->json('fee_breakdown')->nullable();

            $table->timestamps();

            $table->unique(['proposal_request_id', 'product_type', 'product_id'], 'pr_product_rates_request_type_product_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_rates');
    }
};
