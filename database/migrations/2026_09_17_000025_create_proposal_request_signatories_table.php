<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Authorized signatories picked for a Proposal Request - multiple per
 * request, each a live reference to one of the prospect's own contacts
 * (prospect_contacts), not a re-entered name. Removing the underlying
 * contact removes them as a signatory too (cascade).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_signatories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();
            $table->foreignId('prospect_contact_id')->constrained('prospect_contacts')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['proposal_request_id', 'prospect_contact_id'], 'pr_signatory_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_signatories');
    }
};
