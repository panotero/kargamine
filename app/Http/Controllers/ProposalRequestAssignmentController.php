<?php

namespace App\Http\Controllers;

use App\Models\ProposalRequest;
use Illuminate\Http\Request;

/**
 * Proposal Request listings: index() is Management's assignment queue -
 * lists every ProposalRequest with its prospect's current CSR/Relationship
 * Manager assignment, so Management can see (and, via
 * ProspectController::assignOwners, fill in) which prospects still need an
 * owner before the RFP wizard endpoints unlock (see
 * AuthorizesProposalRequestAccess). Deliberately NOT team-scoped -
 * Management sees every request regardless of team hierarchy. mine() is the
 * assigned CSR/RM's own "My Requests" workspace - self-scoped to an exact
 * assigned_to/relationship_manager_id match on the current user.
 */
class ProposalRequestAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $base = fn () => ProposalRequest::query()
            ->with([
                'prospect:id,uuid,assigned_to,relationship_manager_id',
                'prospect.company:id,prospect_id,company_name',
                'prospect.user:id,name',
                'prospect.relationshipManager:id,name',
                'creator:id,name',
            ]);

        // assignment_status: awaiting | assigned | all
        $assignmentFilter = function ($q) use ($request) {
            $status = $request->get('assignment_status', 'all');
            if ($status === 'awaiting') {
                $q->whereHas('prospect', fn ($p) => $p->where(fn ($w) => $w->whereNull('assigned_to')->orWhereNull('relationship_manager_id')));
            } elseif ($status === 'assigned') {
                $q->whereHas('prospect', fn ($p) => $p->whereNotNull('assigned_to')->whereNotNull('relationship_manager_id'));
            }
        };

        $requests = $base()
            ->tap($assignmentFilter)
            ->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('prospect.company', fn ($c) => $c->where('company_name', 'like', "%{$s}%"));
            })
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        $all = ProposalRequest::with('prospect:id,assigned_to,relationship_manager_id')->get();
        $awaiting = $all->filter(fn ($r) => ! $r->prospect || is_null($r->prospect->assigned_to) || is_null($r->prospect->relationship_manager_id))->count();

        return response()->json([
            'success' => true,
            'data' => $requests,
            'status_counts' => [
                'all' => $all->count(),
                'awaiting' => $awaiting,
                'assigned' => $all->count() - $awaiting,
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
            ->with([
                'prospect:id,uuid,assigned_to,relationship_manager_id',
                'prospect.company:id,prospect_id,company_name',
                'prospect.user:id,name',
                'prospect.relationshipManager:id,name',
                'creator:id,name',
            ])
            ->whereHas('prospect', fn ($q) => $q->where('assigned_to', $userId)->orWhere('relationship_manager_id', $userId));

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
