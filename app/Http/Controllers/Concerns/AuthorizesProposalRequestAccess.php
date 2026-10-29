<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Prospect;
use App\Models\User;
use App\Support\RoleHelper;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Locks the Request-for-Proposal wizard's endpoints (ProspectController's
 * proposalRequest* methods + ProposalRequestProductController's product
 * endpoints) down to that SPECIFIC request's assigned CSR/Relationship
 * Manager (or superadmin), and until Management has actually assigned
 * both - staffing is per-request (proposal_requests.assigned_to/
 * relationship_manager_id), not per-prospect, since a prospect can have
 * more than one request over time.
 *
 * Uses HttpResponseException rather than abort() so the failure body still
 * honors this app's {success: false, ...} JSON envelope convention instead
 * of Laravel's bare {message} abort body.
 */
trait AuthorizesProposalRequestAccess
{
    protected function authorizeProspectOwnership(Prospect $lead, ?User $user, $proposalRequestId): void
    {
        $proposalRequest = $lead->proposalRequests()->find($proposalRequestId);

        $ok = $user && (
            RoleHelper::hasAnyRole($user, ['superadmin'])
            || ($proposalRequest && (int) $proposalRequest->assigned_to === (int) $user->id)
            || ($proposalRequest && (int) $proposalRequest->relationship_manager_id === (int) $user->id)
        );

        if (! $ok) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'You are not authorized to work on this proposal request.',
            ], 403));
        }
    }

    protected function authorizeProspectFullyAssigned(Prospect $lead, $proposalRequestId): void
    {
        $proposalRequest = $lead->proposalRequests()->find($proposalRequestId);

        if (! $proposalRequest || ! $proposalRequest->isFullyAssigned()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'This request is awaiting CSR/Relationship Manager assignment by Management.',
            ], 409));
        }
    }
}
