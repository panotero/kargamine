<?php

namespace App\Http\Controllers;

use App\Models\ClientMaster;
use App\Models\ClientProposal;
use App\Models\ClientProposalRate;
use App\Models\Container;
use App\Models\ContainerVariant;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;
use App\Models\Prospect;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\FileUploadService;
use App\Services\TeamNotifier;
use App\Services\TeamService;
use App\Support\PermissionHelper;
use App\Support\RoleHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientProposalController extends Controller
{
    protected $fileUploadService;

    protected ActivityService $activityService;

    public function __construct(FileUploadService $fileUploadService, ActivityService $activityService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->activityService = $activityService;
    }

    /**
     * Resolves the integer prospects.id this proposal should log CRM
     * activity against: its own lead-scoped prospect_id, or - for
     * proposals created via the client-scoped store() path (which never
     * sets prospect_id directly) - the client's originating prospect.
     * Returns null if neither resolves (nothing to log against).
     */
    private function activityProspectId(ClientProposal $proposal): ?int
    {
        if ($proposal->prospect_id) {
            return $proposal->prospect_id;
        }

        $proposal->loadMissing('client.prospect');

        return $proposal->client?->prospect?->id;
    }

    /**
     * Proposals for ONE client - used inside the Client Master detail modal.
     */
    public function index(Request $request, $clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();

        $proposals = ClientProposal::with([
            'rates.originPort.location',
            'rates.originPickupArea',
            'rates.destinationPickupArea',
            'rates.destinationPort.location',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'rates.ancillaryServices',
            'creator:id,name',
            'decidedBy:id,name',
            'activeContract',
            'prospect.user:id,name,team_id',
        ])->where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 5))
            ->appends($request->query());

        return response()->json(['success' => true, 'data' => $proposals]);
    }

    /**
     * ALL proposals across ALL clients - used by the Proposals tab.
     */
    public function indexAll(Request $request)
    {
        // Team-scoped visibility, same rule as the CRM lead list: a regular
        // member only sees proposals for their own leads; a team leader sees
        // their team's subtree; superadmin bypasses entirely. A proposal's
        // "owner" is its lead's assigned rep - checked via the proposal's own
        // prospect_id first, falling back to the client's originating lead for
        // proposals created via the client-scoped store() path (which never
        // sets prospect_id directly).
        $visibleUserIds = RoleHelper::hasAnyRole($request->user(), ['superadmin'])
            ? null
            : TeamService::accessibleUserIds($request->user());

        $visibilityScope = function ($q) use ($visibleUserIds) {
            $q->when($visibleUserIds !== null, function ($q) use ($visibleUserIds) {
                $q->where(function ($q) use ($visibleUserIds) {
                    $q->whereHas('prospect', fn ($q) => $q->whereIn('assigned_to', $visibleUserIds))
                        ->orWhereHas('client.prospect', fn ($q) => $q->whereIn('assigned_to', $visibleUserIds));
                });
            });
        };

        $proposals = ClientProposal::with([
            'client:id,uuid,company_name,customer_code,sales_rep_id',
            'creator:id,name',
            'decidedBy:id,name',
            'prospect.user:id,name,team_id',
        ])
            ->tap($visibilityScope)
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('client', fn ($q) => $q->where('company_name', 'like', "%{$s}%"));
            })
            ->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        $statusCounts = ClientProposal::tap($visibilityScope)->get()->groupBy('status')->map(fn ($group) => $group->count());

        $pendingAwaitingDecision = ClientProposal::tap($visibilityScope)
            ->whereIn('status', [ClientProposal::STATUS_PENDING, ClientProposal::STATUS_PENDING_MANAGER])
            ->with(['prospect.user:id,team_id', 'client.prospect.user:id,team_id'])
            ->get()
            ->filter(fn ($p) => $p->canBeApprovedBy($request->user()))
            ->count();

        return response()->json([
            'success' => true,
            'data' => $proposals,
            'status_counts' => [
                'all' => $statusCounts->sum(),
                'awaiting_decision' => $pendingAwaitingDecision,
                'pending' => $statusCounts->get(ClientProposal::STATUS_PENDING, 0),
                'pending_manager' => $statusCounts->get(ClientProposal::STATUS_PENDING_MANAGER, 0),
                'approved' => $statusCounts->get(ClientProposal::STATUS_APPROVED, 0),
                'disapproved' => $statusCounts->get(ClientProposal::STATUS_DISAPPROVED, 0),
                'accepted' => $statusCounts->get(ClientProposal::STATUS_ACCEPTED, 0),
                'rejected' => $statusCounts->get(ClientProposal::STATUS_REJECTED, 0),
                'cancelled' => $statusCounts->get(ClientProposal::STATUS_CANCELLED, 0),
            ],
        ]);
    }

    public function show(ClientProposal $proposal)
    {
        $proposal->load([
            'client',
            'creator',
            'decidedBy',
            'prospect.company',
            'prospect.addresses',
            // team_id is required here, not just display fields - canBeApprovedBy()/
            // canBeSignedBy() read it via ownerUser()->team_id, and a column-
            // restricted eager load silently nulls out anything not selected.
            'prospect.user:id,name,team_id',
            // Client-scoped proposals (created via store()) never set prospect_id
            // directly - this is the same fallback as ClientProposal::ownerUser(),
            // so the modal can still show lead info for those.
            'client.prospect.company',
            'client.prospect.user:id,name,team_id',
            'rates.originPort.location',
            'rates.originPickupArea',
            'rates.destinationPickupArea',
            'rates.destinationPort.location',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'rates.ancillaryServices',
            'activeContract',
        ]);

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    public function rateLookup(Request $request)
    {
        $validated = $request->validate([
            'origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'container_variant_id' => ['required', 'integer', 'exists:container_variants,id'],
        ]);

        $lane = Lane::where('origin_port_id', $validated['origin_port_id'])
            ->where('destination_port_id', $validated['destination_port_id'])
            ->where('is_active', true)
            ->first();

        if (! $lane) {
            return response()->json(['success' => false, 'message' => 'No active lane for this origin/destination.'], 404);
        }

        $tariffRate = LaneTariffRate::where('lane_id', $lane->lane_id)
            ->activeOn()->orderByDesc('effective_date')->first();

        if (! $tariffRate) {
            return response()->json(['success' => false, 'message' => 'No active tariff rate for this lane.'], 404);
        }

        $price = LaneTariffRatePrice::where('lane_tariff_rate_id', $tariffRate->rate_id)
            ->where('container_variant_id', $validated['container_variant_id'])
            ->first();

        if (! $price) {
            return response()->json(['success' => false, 'message' => 'No price configured for this container on this lane.'], 404);
        }

        return response()->json(['success' => true, 'data' => ['frt' => (float) $price->frt]]);
    }

    public function store(Request $request, $clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();
        $validated = $request->validate($this->rateRules());

        $proposal = DB::transaction(function () use ($client, $validated, $request) {
            $proposal = $this->createProposal($client, $request);

            foreach ($validated['rates'] as $rateData) {
                $this->createRateWithAncillaryServices($proposal, $rateData);
            }

            return $proposal;
        });

        try {
            if ($pid = $this->activityProspectId($proposal)) {
                $this->activityService->create(
                    $pid,
                    'proposal_submitted',
                    'Proposal '.$proposal->code.' submitted for approval.',
                    $request->user()?->id
                );
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $this->notifyProposalPending($proposal);

        return response()->json(['success' => true, 'data' => $proposal->load('rates.ancillaryServices')], 201);
    }

    /**
     * "Add Container" - only while the proposal is still pending.
     */
    public function addRates(Request $request, ClientProposal $proposal)
    {
        if ($proposal->status !== ClientProposal::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Containers can only be added while the proposal is pending.',
            ], 422);
        }

        $validated = $request->validate($this->rateRules());

        DB::transaction(function () use ($proposal, $validated) {
            foreach ($validated['rates'] as $rateData) {
                $this->createRateWithAncillaryServices($proposal, $rateData);
            }
        });

        return response()->json(['success' => true, 'data' => $proposal->load('rates.ancillaryServices')]);
    }

    public function destroyRate(ClientProposalRate $rate)
    {
        $rate->delete();

        return response()->json(['success' => true, 'data' => null]);
    }

    // ---------------------------------------------------------------
    // Approval workflow
    // ---------------------------------------------------------------

    public function approve(Request $request, ClientProposal $proposal)
    {
        if (! $proposal->canBeApprovedBy($request->user())) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to approve proposals.'], 403);
        }

        if (! in_array($proposal->status, [ClientProposal::STATUS_PENDING, ClientProposal::STATUS_PENDING_MANAGER], true)) {
            return response()->json(['success' => false, 'message' => 'Only proposals pending RM or Manager approval can be approved.'], 422);
        }

        $validated = $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);

        // Stage 1 (RM) is a forward-only checkpoint - it moves the proposal
        // to the Manager's queue without touching decided_by/decided_at,
        // which stay reserved for whichever stage makes the final call.
        if ($proposal->status === ClientProposal::STATUS_PENDING) {
            $proposal->update([
                'status' => ClientProposal::STATUS_PENDING_MANAGER,
                'rm_approved_by' => $request->user()->id,
                'rm_approved_at' => now(),
            ]);

            try {
                if ($pid = $this->activityProspectId($proposal)) {
                    $this->activityService->create(
                        $pid,
                        'proposal_rm_approved',
                        'Proposal '.$proposal->code.' approved by RM and forwarded to Manager.',
                        $request->user()->id
                    );
                }
            } catch (\Throwable $e) {
                report($e);
            }

            $this->notifyProposalPendingManager($proposal);

            return response()->json(['success' => true, 'data' => $proposal]);
        }

        $proposal->update([
            'status' => ClientProposal::STATUS_APPROVED,
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'decision_remarks' => $validated['remarks'] ?? null,
        ]);

        $proposal->proposalRequest?->update(['status' => 'approved']);

        try {
            if ($pid = $this->activityProspectId($proposal)) {
                $description = 'Proposal '.$proposal->code.' approved.';
                if (! empty($validated['remarks'])) {
                    $description .= ' Remarks: '.$validated['remarks'];
                }
                $this->activityService->create($pid, 'proposal_approved', $description, $request->user()->id);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $this->notifyProposalDecision($proposal, $request->user(), 'approved');

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    public function disapprove(Request $request, ClientProposal $proposal)
    {
        if (! $proposal->canBeApprovedBy($request->user())) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to disapprove proposals.'], 403);
        }

        if (! in_array($proposal->status, [ClientProposal::STATUS_PENDING, ClientProposal::STATUS_PENDING_MANAGER], true)) {
            return response()->json(['success' => false, 'message' => 'Only proposals pending RM or Manager approval can be disapproved.'], 422);
        }

        $validated = $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);

        $proposal->update([
            'status' => ClientProposal::STATUS_DISAPPROVED,
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'decision_remarks' => $validated['remarks'] ?? null,
        ]);

        try {
            if ($pid = $this->activityProspectId($proposal)) {
                $description = 'Proposal '.$proposal->code.' disapproved.';
                if (! empty($validated['remarks'])) {
                    $description .= ' Remarks: '.$validated['remarks'];
                }
                $this->activityService->create($pid, 'proposal_disapproved', $description, $request->user()->id);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $this->notifyProposalDecision($proposal, $request->user(), 'disapproved');

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    public function reject(Request $request, ClientProposal $proposal)
    {
        if (! $proposal->canBeRejectedBy($request->user())) {
            return response()->json(['success' => false, 'message' => 'Only the account manager for this client can reject the proposal.'], 403);
        }

        if (in_array($proposal->status, [ClientProposal::STATUS_ACCEPTED, ClientProposal::STATUS_REJECTED], true)) {
            return response()->json(['success' => false, 'message' => 'This proposal can no longer be rejected.'], 422);
        }

        $validated = $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);

        $proposal->update([
            'status' => ClientProposal::STATUS_REJECTED,
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'decision_remarks' => $validated['remarks'] ?? null,
        ]);

        try {
            if ($pid = $this->activityProspectId($proposal)) {
                $description = 'Proposal '.$proposal->code.' rejected.';
                if (! empty($validated['remarks'])) {
                    $description .= ' Remarks: '.$validated['remarks'];
                }
                $this->activityService->create($pid, 'proposal_rejected', $description, $request->user()->id);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $this->notifyProposalDecision($proposal, $request->user(), 'rejected');

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    /**
     * Only available while pending RM or Manager approval - once a decision
     * has been made (approved/disapproved/etc.) the proposal follows the
     * rest of the workflow instead (reject, re-approve, etc.).
     */
    public function cancel(Request $request, ClientProposal $proposal)
    {
        if (! $proposal->canBeCancelledBy($request->user())) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to cancel this proposal.'], 403);
        }

        if (! in_array($proposal->status, [ClientProposal::STATUS_PENDING, ClientProposal::STATUS_PENDING_MANAGER], true)) {
            return response()->json(['success' => false, 'message' => 'Only proposals pending RM or Manager approval can be cancelled.'], 422);
        }

        $validated = $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);

        $proposal->update([
            'status' => ClientProposal::STATUS_CANCELLED,
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'decision_remarks' => $validated['remarks'] ?? null,
        ]);

        try {
            if ($pid = $this->activityProspectId($proposal)) {
                $description = 'Proposal '.$proposal->code.' cancelled.';
                if (! empty($validated['remarks'])) {
                    $description .= ' Remarks: '.$validated['remarks'];
                }
                $this->activityService->create($pid, 'proposal_cancelled', $description, $request->user()->id);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    /**
     * Uploading the signed copy is what promotes an APPROVED proposal to ACCEPTED.
     */
    public function attachSigned(Request $request, ClientProposal $proposal)
    {
        if (! $proposal->canBeSignedBy($request->user())) {
            return response()->json(['success' => false, 'message' => 'Only the lead\'s team can upload the signed document.'], 403);
        }

        if ($proposal->status !== ClientProposal::STATUS_APPROVED) {
            return response()->json(['success' => false, 'message' => 'Only approved proposals can be marked as signed.'], 422);
        }

        $validated = $request->validate([
            'signed_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $path = $this->fileUploadService->uploadFile(
            [$validated['signed_document']], // must be array
            'uploads/doc/pdf'
        );

        $proposal->update([
            'signed_document_path' => $path,
            'signed_at' => now(),
            'status' => ClientProposal::STATUS_ACCEPTED,
        ]);

        $proposal->proposalRequest?->update(['status' => 'signed']);

        try {
            if ($pid = $this->activityProspectId($proposal)) {
                $this->activityService->create(
                    $pid,
                    'proposal_signed',
                    'Signed document uploaded — proposal '.$proposal->code.' accepted.',
                    $request->user()->id
                );
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    public function downloadPdf(ClientProposal $proposal)
    {
        $proposal->load([
            'client',
            'client.addresses',
            'prospect.company',
            'prospect.addresses',
            'creator',
            'rates.originPort.location',
            'rates.originPickupArea',
            'rates.destinationPickupArea',
            'rates.destinationPort.location',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'rates.ancillaryServices',
        ]);

        $pdf = Pdf::loadView('pdf.clientProposal', ['proposal' => $proposal]);

        return $pdf->download($proposal->code.'.pdf');
    }

    protected function rateRules(): array
    {
        return [
            'rates' => ['required', 'array', 'min:1'],
            'rates.*.origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'rates.*.origin_pickup_area_id' => ['nullable', 'integer', 'exists:serviceable_areas,area_id'],
            'rates.*.destination_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'rates.*.destination_pickup_area_id' => ['nullable', 'integer', 'exists:serviceable_areas,area_id'],
            'rates.*.container_id' => ['required', 'integer', 'exists:containers,id'],
            'rates.*.container_class_id' => ['nullable', 'integer', 'exists:container_class,id'],
            'rates.*.container_size_id' => ['nullable', 'integer', 'exists:container_size,id'],
            'rates.*.container_variant_id' => ['required', 'integer', 'exists:container_variants,id'],
            'rates.*.min_van_qty' => ['nullable', 'integer', 'min:1'],
            'rates.*.base_rate' => ['required', 'numeric', 'min:0'],
            'rates.*.discount_type' => ['nullable', 'in:percentage,fixed,increase_percentage,increase_fixed'],
            'rates.*.discount_value' => ['nullable', 'numeric', 'min:0'],
            'rates.*.final_rate' => ['required', 'numeric', 'min:0'],
            'rates.*.ancillary_services' => ['nullable', 'array'],
            'rates.*.ancillary_services.*.required_service' => ['nullable', 'string', 'max:255'],
            'rates.*.ancillary_services.*.location' => ['nullable', 'string', 'max:255'],
            'rates.*.ancillary_services.*.unit' => ['nullable', 'string', 'max:255'],
            'rates.*.ancillary_services.*.quantity' => ['nullable', 'numeric', 'min:0'],

            // Proposal-wide opt-ins (not per rate) - only meaningful at
            // creation time (store()/storeForLead()); harmless no-ops when
            // present on addRates() since that path never reads them.
            'include_special_charges' => ['nullable', 'boolean'],
            'include_port_charges' => ['nullable', 'boolean'],
            'include_handling_fee' => ['nullable', 'boolean'],
            'include_general_charges' => ['nullable', 'boolean'],
        ];
    }

    /**
     * ancillary_services isn't a client_proposal_rates column - pull it out
     * before mass-assigning the rate, then create it as nested rows once the
     * rate itself exists (it needs the rate's id).
     */
    protected function createRateWithAncillaryServices(ClientProposal $proposal, array $rateData): ClientProposalRate
    {
        $ancillaryServices = $rateData['ancillary_services'] ?? [];
        unset($rateData['ancillary_services']);

        $rate = $proposal->rates()->create($rateData);

        foreach ($ancillaryServices as $service) {
            $rate->ancillaryServices()->create($service);
        }

        return $rate;
    }

    protected function createProposal(ClientMaster $client, Request $request): ClientProposal
    {
        $yearMonth = Carbon::now()->format('Ym');
        $last = ClientProposal::where('code', 'like', "CPR-{$yearMonth}%")
            ->orderByDesc('id')->lockForUpdate()->first();
        $seq = $last ? ((int) substr($last->code, -4)) + 1 : 1;

        return ClientProposal::create([
            'uuid' => (string) Str::uuid(),
            'code' => sprintf('CPR-%s-%04d', $yearMonth, $seq),
            'client_id' => $client->id,
            'proposal_request_id' => $request->input('proposal_request_id'),
            'status' => ClientProposal::STATUS_PENDING,
            'created_by' => $request->user()?->id,
            'include_special_charges' => $request->boolean('include_special_charges'),
            'include_port_charges' => $request->boolean('include_port_charges'),
            'include_handling_fee' => $request->boolean('include_handling_fee'),
            'include_general_charges' => $request->boolean('include_general_charges'),
        ]);
    }

    /**
     * Fired at creation (status STATUS_PENDING) - notifies the deal's
     * assigned Relationship Manager directly, since RM approval is now a
     * per-deal identity (prospects.relationship_manager_id), not a team
     * lookup. Silently skipped if no RM is assigned yet.
     */
    protected function notifyProposalPending(ClientProposal $proposal): void
    {
        $owner = $proposal->ownerUser();
        $rm = $proposal->assignedRelationshipManager();

        if (! $owner || ! $rm) {
            return;
        }

        TeamNotifier::notify([$rm->id], [
            'type' => 'proposal.pending',
            'title' => 'Proposal awaiting your approval',
            'message' => "{$proposal->code} for {$owner->name} needs your approval.",
            'from_user_id' => $owner->id,
            'notifiable' => $proposal,
            'link' => ['title' => 'View Proposal', 'url' => '/page_proposals'],
            'email_subject' => "Approval needed — {$proposal->code}",
            // Clicking this notification opens the Proposals page AND the
            // specific proposal's modal - this one needs the RM's eyes on
            // it, not just a landing on the page (unlike the CRM new-lead
            // notification, which only opens the CRM page).
            'data' => ['modal_fn' => 'openProposalModal', 'modal_args' => [$proposal->id]],
        ]);
    }

    /**
     * Fired when the RM approves/forwards (status STATUS_PENDING_MANAGER) -
     * notifies every user whose role currently holds the
     * "proposal.approve.manager" permission, since the Manager approval
     * isn't scoped to a single assigned individual.
     */
    protected function notifyProposalPendingManager(ClientProposal $proposal): void
    {
        $owner = $proposal->ownerUser();
        $managerIds = PermissionHelper::userIdsWithPermission('proposal.approve.manager');

        if (! $owner || ! $managerIds) {
            return;
        }

        TeamNotifier::notify($managerIds, [
            'type' => 'proposal.pending_manager',
            'title' => 'Proposal awaiting your final approval',
            'message' => "{$proposal->code} for {$owner->name} was approved by the RM and needs your final decision.",
            'from_user_id' => $owner->id,
            'notifiable' => $proposal,
            'link' => ['title' => 'View Proposal', 'url' => '/page_proposals'],
            'email_subject' => "Final approval needed — {$proposal->code}",
            'data' => ['modal_fn' => 'openProposalModal', 'modal_args' => [$proposal->id]],
        ]);
    }

    /**
     * Once a decision is made (approved/disapproved/rejected), notify the
     * proposal's creator - regardless of which authorization path decided it.
     */
    protected function notifyProposalDecision(ClientProposal $proposal, User $decidedBy, string $decision): void
    {
        $creator = $proposal->creator;

        if (! $creator) {
            return;
        }

        TeamNotifier::notify([$creator->id], [
            'type' => "proposal.{$decision}",
            'title' => "Proposal {$decision}",
            'message' => "{$proposal->code} was {$decision} by {$decidedBy->name}.",
            'from_user_id' => $decidedBy->id,
            'notifiable' => $proposal,
            'link' => ['title' => 'View Proposal', 'url' => '/page_proposals'],
            'email_subject' => "Your proposal was {$decision} — {$proposal->code}",
            'data' => ['modal_fn' => 'openProposalModal', 'modal_args' => [$proposal->id]],
        ]);
    }

    public function indexByLead(Request $request, $leadUuid)
    {
        $lead = Prospect::where('uuid', $leadUuid)->firstOrFail();

        $proposals = ClientProposal::with([
            'rates.originPort.location',
            'rates.originPickupArea',
            'rates.destinationPickupArea',
            'rates.destinationPort.location',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'rates.ancillaryServices',
            'creator:id,name',
            'decidedBy:id,name',
        ])->where('prospect_id', $lead->id)
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 5))
            ->appends($request->query());

        return response()->json(['success' => true, 'data' => $proposals]);
    }

    public function storeForLead(Request $request, $leadUuid)
    {
        $lead = Prospect::where('uuid', $leadUuid)->firstOrFail();
        $validated = $request->validate($this->rateRules());

        $proposal = DB::transaction(function () use ($lead, $validated, $request) {
            $yearMonth = Carbon::now()->format('Ym');
            $last = ClientProposal::where('code', 'like', "CPR-{$yearMonth}%")
                ->orderByDesc('id')->lockForUpdate()->first();
            $seq = $last ? ((int) substr($last->code, -4)) + 1 : 1;

            $proposal = ClientProposal::create([
                'uuid' => (string) Str::uuid(),
                'code' => sprintf('CPR-%s-%04d', $yearMonth, $seq),
                'prospect_id' => $lead->id,
                'client_id' => null,
                'proposal_request_id' => $request->input('proposal_request_id'),
                'status' => ClientProposal::STATUS_PENDING,
                'created_by' => $request->user()?->id,
                'include_special_charges' => $request->boolean('include_special_charges'),
                'include_port_charges' => $request->boolean('include_port_charges'),
                'include_handling_fee' => $request->boolean('include_handling_fee'),
                'include_general_charges' => $request->boolean('include_general_charges'),
            ]);

            foreach ($validated['rates'] as $rateData) {
                $this->createRateWithAncillaryServices($proposal, $rateData);
            }

            return $proposal;
        });

        try {
            $this->activityService->create(
                $lead->id,
                'proposal_submitted',
                'Proposal '.$proposal->code.' submitted for approval.',
                $request->user()?->id
            );
        } catch (\Throwable $e) {
            report($e);
        }

        $this->notifyProposalPending($proposal);

        return response()->json(['success' => true, 'data' => $proposal->load('rates.ancillaryServices')], 201);
    }

    /**
     * Builds pre-filled (best-effort) proposal rate rows from the lead's
     * own container requirements (prospect_containers_legacy), so the "New Proposal"
     * form opens already populated. The frontend still lets the user edit,
     * add, or remove any row before saving.
     *
     * Variant matching tolerates null class and/or size on both sides: a
     * container that has no classes (e.g. Flat Rack, Reefer Van) or no
     * sizes (Loose Cargo, Rolling Cargo - priced by class only) will only
     * ever have variants with that side null, so the lookup matches null
     * to null rather than requiring both to be set.
     */
    public function leadContainerDefaults($leadUuid)
    {
        $lead = Prospect::where('uuid', $leadUuid)->firstOrFail();

        $rows = $lead->containers()
            ->with(['originPort.location', 'destinationPort.location', 'containerClass', 'containerSize'])
            ->get()
            ->map(function ($lc) {
                $container = Container::where('code', $lc->container_type)->first();
                $variant = null;
                $baseRate = null;

                if ($container) {
                    $variant = ContainerVariant::where('container_id', $container->id)
                        ->where(function ($q) use ($lc) {
                            $lc->container_class_id
                                ? $q->where('container_class_id', $lc->container_class_id)
                                : $q->whereNull('container_class_id');
                        })
                        ->where(function ($q) use ($lc) {
                            $lc->container_size_id
                                ? $q->where('container_size_id', $lc->container_size_id)
                                : $q->whereNull('container_size_id');
                        })
                        ->first();
                }

                if ($variant && $lc->origin_port_id && $lc->destination_port_id) {
                    $lookup = $this->resolveRate($lc->origin_port_id, $lc->destination_port_id, $variant->id);
                    $baseRate = $lookup['frt'] ?? null;
                }

                return [
                    'source_container_id' => $lc->id,
                    'container_type' => $lc->container_type,
                    'origin_port_id' => $lc->origin_port_id,
                    'destination_port_id' => $lc->destination_port_id,
                    'container_id' => $container?->id,
                    'container_class_id' => $lc->container_class_id,
                    'container_size_id' => $lc->container_size_id,
                    'container_variant_id' => $variant?->id,
                    'base_rate' => $baseRate,
                ];
            });

        return response()->json(['success' => true, 'data' => $rows]);
    }

    /**
     * Shared lane/tariff resolution - factored out of rateLookup() so both
     * the manual lookup endpoint and the auto-prefill above use one path.
     */
    protected function resolveRate($originPortId, $destinationPortId, $containerVariantId): array
    {
        $lane = Lane::where('origin_port_id', $originPortId)
            ->where('destination_port_id', $destinationPortId)
            ->where('is_active', true)->first();
        if (! $lane) {
            return [];
        }

        $tariffRate = LaneTariffRate::where('lane_id', $lane->lane_id)
            ->activeOn()->orderByDesc('effective_date')->first();
        if (! $tariffRate) {
            return [];
        }

        $price = LaneTariffRatePrice::where('lane_tariff_rate_id', $tariffRate->rate_id)
            ->where('container_variant_id', $containerVariantId)->first();

        return $price ? ['frt' => (float) $price->frt] : [];
    }
}
