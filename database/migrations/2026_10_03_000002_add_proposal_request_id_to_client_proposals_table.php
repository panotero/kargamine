<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Links a commercial ClientProposal back to the ProposalRequest (RFP) it was
 * built from, if any - needed so approving/signing a ClientProposal can sync
 * the matching status onto its ProposalRequest (see
 * ClientProposalController::approve()/attachSigned()). Nullable: a
 * ClientProposal created without a specific request simply has no link and
 * is not included in that sync.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->unsignedBigInteger('proposal_request_id')->nullable()->after('prospect_id');
            $table->foreign('proposal_request_id')->references('id')->on('proposal_requests')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->dropForeign(['proposal_request_id']);
            $table->dropColumn('proposal_request_id');
        });
    }
};
