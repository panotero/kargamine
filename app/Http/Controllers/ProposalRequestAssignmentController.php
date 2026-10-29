<?php

namespace App\Http\Controllers;

use App\Models\ClientProposal;
use App\Models\ProposalRequest;
use App\Services\TeamService;
use App\Support\RoleHelper;
use Illuminate\Http\Request;

/**
 * Proposal Request listings: index() is the combined Requests / Approvals /
 * Signed-Contracts list - every ProposalRequest together with its latest
 * ClientProposal's status folded into a single `effective_status` (see
 * ProposalRequest::getEffectiveStatusAttribute()), visible either via the
 * prospect's own created_by/owner_id team scope (same as
 * ProspectController::index()) or via this request's OWN assigned_to/
 * relationship_manager_id - staffing lives on the request, not the
 * prospect, so assigning one request never changes what else is visible.
 * Management (superadmin/admin/developer) bypasses the scoping entirely.
 * Per-row `can_assign`/`can_open_my_request` flags tell the frontend which
 * action buttons to show for the current user. mine() (below) is the
 * assigned CSR/RM's own "My Requests" workspace - self-scoped to an exact
 * assigned_to/relationship_manager_id match on the current user, against
 * the request directly.
 */
class ProposalRequestAssignmentController extends Controller
{
    /**
     * Effective-status filter values that map onto the latest
     * ClientProposal's integer status. whereHas('clientProposals', ...)
     * rather than filtering on latestClientProposal only is a pragmatic
     * approximation - in practice every ProposalRequest has at most one
     * ClientProposal, so there is no ambiguity between "any" and "latest".
     */
    private const CLIENT_PROPOSAL_STATUS_FILTERS = [
        'pending_rm' => ClientProposal::STATUS_PENDING,
        'pending_manager' => ClientProposal::STATUS_PENDING_MANAGER,
        'approved' => ClientProposal::STATUS_APPROVED,
        'accepted' => ClientProposal::STATUS_ACCEPTED,
        'disapproved' => ClientProposal::STATUS_DISAPPROVED,
        'rejected' => ClientProposal::STATUS_REJECTED,
        'proposal_cancelled' => ClientProposal::STATUS_CANCELLED,
    ];

    public function index(Request $request)
    {
        $user = $request->user();

        // Team-scoped visibility: a row's visibility tracks its prospect's
        // visibility, mirroring ProspectController::index(). Management
        // bypasses entirely and sees every request.
        $visibleUserIds = RoleHelper::hasAnyRole($user, ['superadmin', 'developer', 'admin'])
            ? null
            : TeamService::accessibleTeamUserIds($user);

        // Visibility tracks the prospect's own created_by/owner_id (the CRM
        // edit tier - orthogonal to staffing), OR'd with this request's OWN
        // assigned_to/relationship_manager_id (staffing now lives here, not
        // on the prospect) - so a specifically-staffed CSR/RM keeps seeing
        // their row even outside the creator's team hierarchy.
        $visibilityScope = function ($q) use ($visibleUserIds) {
            $q->when($visibleUserIds !== null, function ($q) use ($visibleUserIds) {
                $q->where(function ($q2) use ($visibleUserIds) {
                    $q2->whereHas('prospect', function ($p) use ($visibleUserIds) {
                        $p->whereIn('created_by', $visibleUserIds)
                            ->orWhereIn('owner_id', $visibleUserIds);
                    })
                        ->orWhereIn('assigned_to', $visibleUserIds)
                        ->orWhereIn('relationship_manager_id', $visibleUserIds);
                });
            });
        };

        $base = fn () => ProposalRequest::query()
            ->with([
                'prospect:id,uuid,created_by,owner_id',
                'prospect.company:id,prospect_id,company_name',
                'user:id,name',
                'relationshipManager:id,name',
                'creator:id,name',
                'latestClientProposal.prospect.user:id,name,team_id',
                'latestClientProposal.client.prospect.user:id,name,team_id',
                'latestClientProposal.activeContract',
            ])
            ->tap($visibilityScope);

        // effective_status: one of the values from
        // ProposalRequest::getEffectiveStatusAttribute(), or 'all'.
        $effectiveStatusFilter = function ($q) use ($request) {
            $status = $request->get('effective_status', 'all');

            if ($status === 'all') {
                return;
            }

            if ($status === 'for_assignment') {
                $q->where('status', 'pending')->whereDoesntHave('clientProposals');
            } elseif ($status === 'assigned') {
                $q->where('status', 'assigned')->whereDoesntHave('clientProposals');
            } elseif ($status === 'cancelled') {
                $q->where('status', 'cancelled');
            } elseif (array_key_exists($status, self::CLIENT_PROPOSAL_STATUS_FILTERS)) {
                $proposalStatus = self::CLIENT_PROPOSAL_STATUS_FILTERS[$status];
                $q->whereHas('clientProposals', fn ($cp) => $cp->where('status', $proposalStatus));
            }
        };

        $requests = $base()
            ->tap($effectiveStatusFilter)
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('prospect.company', fn ($c) => $c->where('company_name', 'like', "%{$s}%"));
            })
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        $requests->getCollection()->each(function ($row) use ($user) {
            $row->append('effective_status');

            $row->setAttribute(
                'can_assign',
                RoleHelper::hasAnyRole($user, ['superadmin', 'admin']) && $row->effective_status === 'for_assignment'
            );

            $row->setAttribute(
                'can_open_my_request',
                (int) $row->assigned_to === (int) $user->id
                    || (int) $row->relationship_manager_id === (int) $user->id
            );
        });

        $all = ProposalRequest::query()
            // Column-restricted eager load must qualify with the table name -
            // latestOfMany()'s generated join aliases its own
            // proposal_request_id column, and an unqualified bare column
            // name here collides with it ("ambiguous column" SQL error).
            ->with('latestClientProposal:client_proposals.id,client_proposals.proposal_request_id,client_proposals.status')
            ->tap($visibilityScope)
            ->get()
            ->each(fn ($row) => $row->append('effective_status'));

        $statusCounts = $all->countBy('effective_status');

        return response()->json([
            'success' => true,
            'data' => $requests,
            'status_counts' => [
                'all' => $all->count(),
                'for_assignment' => $statusCounts->get('for_assignment', 0),
                'assigned' => $statusCounts->get('assigned', 0),
                'pending_rm' => $statusCounts->get('pending_rm', 0),
                'pending_manager' => $statusCounts->get('pending_manager', 0),
                'approved' => $statusCounts->get('approved', 0),
                'accepted' => $statusCounts->get('accepted', 0),
                'disapproved' => $statusCounts->get('disapproved', 0),
                'rejected' => $statusCounts->get('rejected', 0),
                'proposal_cancelled' => $statusCounts->get('proposal_cancelled', 0),
                'cancelled' => $statusCounts->get('cancelled', 0),
            ],
        ]);
    }

    /**
     * "My Requests" - every ProposalRequest assigned to the current user as
     * either the prospect's CSR (assigned_to) or Relationship Manager
     * (relationship_manager_id). Unlike index() above, this is inherently
     * self-scoped (exact user match, not team hierarchy) - no superadmin
     * bypass needed, since every authenticated user legitimately sees only
     * their own assigned rows by construction.
     */
    public function mine(Request $request)
    {
        $userId = $request->user()->id;

        $base = fn () => ProposalRequest::query()
            ->with(array_merge(
                [
                    'prospect:id,uuid',
                    'prospect.company:id,prospect_id,company_name',
                    'user:id,name',
                    'relationshipManager:id,name',
                    'creator:id,name',
                    'latestClientProposal',
                    'latestClientProposal.prospect.user:id,name,team_id',
                    'latestClientProposal.client.prospect.user:id,name,team_id',
                ],
                ProposalRequest::fullTreeEagerLoadSuffixes(),
            ))
            ->where(fn ($q) => $q->where('assigned_to', $userId)->orWhere('relationship_manager_id', $userId));

        $requests = $base()
            ->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('prospect.company', fn ($c) => $c->where('company_name', 'like', "%{$s}%"));
            })
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        $requests->getCollection()->each(function ($row) use ($request) {
            $cp = $row->latestClientProposal;
            $row->setAttribute('client_proposal_id', $cp?->id);
            $row->setAttribute(
                'can_upload_signed',
                (bool) $cp
                    && $cp->status === ClientProposal::STATUS_APPROVED
                    && $cp->canBeSignedBy($request->user())
            );
        });

        $statusCounts = $base()->get()->groupBy('status')->map(fn ($group) => $group->count());

        return response()->json([
            'success' => true,
            'data' => $requests,
            'status_counts' => [
                'all' => $statusCounts->sum(),
                'assigned' => $statusCounts->get('assigned', 0),
                'for_approval' => $statusCounts->get('for_approval', 0),
                'approved' => $statusCounts->get('approved', 0),
                'signed' => $statusCounts->get('signed', 0),
                'cancelled' => $statusCounts->get('cancelled', 0),
            ],
        ]);
    }
}
