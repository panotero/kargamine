<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The Origin & Destination tab's curated location list - multiple per
 * Proposal Request, each a live reference to one of the prospect's own
 * addresses (prospect_locations). This list is what the (future) Products
 * tab's origin/destination selects will draw from, and it's what makes the
 * "Origin & Destination" tab on the Prospect Info modal appear once it has
 * at least one row.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();
            $table->foreignId('prospect_location_id')->constrained('prospect_locations')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['proposal_request_id', 'prospect_location_id'], 'pr_location_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_locations');
    }
};
