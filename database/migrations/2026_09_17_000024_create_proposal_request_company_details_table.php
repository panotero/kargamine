<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per ProposalRequest - the Company Details tab of the Request for
 * Proposal wizard. company_name is an editable snapshot (prefilled from the
 * prospect's company name but stored independently so it doesn't drift if
 * the prospect's own record changes later); prospect_location_id is a live
 * reference to one of the prospect's own addresses (prospect_locations),
 * not a copy - display fields are always read through the relation.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_company_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->unique()->constrained('proposal_requests')->cascadeOnDelete();
            $table->string('company_name')->nullable();
            $table->foreignId('prospect_location_id')->nullable()->constrained('prospect_locations')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_company_details');
    }
};
