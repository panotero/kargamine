<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * One-time rename of ProposalRequest.status values to the new end-to-end
 * lifecycle (pending -> assigned -> for_approval -> approved -> signed ->
 * cancelled), replacing the old draft/pending/cancelled set. 'draft' splits
 * into 'pending' (not yet assigned) or 'assigned' (CSR+RM already set on the
 * prospect) depending on existing data; old 'pending' (meant "submitted")
 * becomes 'for_approval'. 'cancelled' is untouched. See
 * ProspectController::storeProposalRequestNew()/submitProposalRequest()/
 * assignOwners() and ClientProposalController::approve()/attachSigned() for
 * where each new value is now set.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('proposal_requests')
            ->where('status', 'pending')
            ->update(['status' => 'for_approval']);

        DB::table('proposal_requests')
            ->join('prospects', 'prospects.id', '=', 'proposal_requests.prospect_id')
            ->where('proposal_requests.status', 'draft')
            ->whereNotNull('prospects.assigned_to')
            ->whereNotNull('prospects.relationship_manager_id')
            ->update(['proposal_requests.status' => 'assigned']);

        DB::table('proposal_requests')
            ->where('status', 'draft')
            ->update(['status' => 'pending']);
    }

    public function down(): void
    {
        // Order matters: move current assigned/pending back to draft FIRST,
        // then free up 'pending' by moving for_approval into it - otherwise
        // the second step would also catch rows the first step just wrote.
        DB::table('proposal_requests')
            ->whereIn('status', ['assigned', 'pending'])
            ->update(['status' => 'draft']);

        DB::table('proposal_requests')
            ->where('status', 'for_approval')
            ->update(['status' => 'pending']);
    }
};
