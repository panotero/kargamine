<?php

namespace App\Http\Controllers;

use App\Models\ClientMaster;
use App\Models\ClientProposal;
use App\Models\ClientProposalRate;
use App\Models\Container;
use App\Models\ContainerVariant;
use App\Models\CrmLead;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;
use App\Services\FileUploadService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientProposalController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Proposals for ONE client - used inside the Client Master detail modal.
     */
    public function index(Request $request, $clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();

        $proposals = ClientProposal::with([
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'creator:id,name',
            'decidedBy:id,name',
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
        $proposals = ClientProposal::with([
            'client:id,uuid,company_name,customer_code,sales_rep_id',
            'creator:id,name',
            'decidedBy:id,name',
        ])
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('client', fn ($q) => $q->where('company_name', 'like', "%{$s}%"));
            })
            ->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        return response()->json(['success' => true, 'data' => $proposals]);
    }

    public function show(ClientProposal $proposal)
    {
        $proposal->load([
            'client',
            'creator',
            'decidedBy',
            'lead.company',
            'lead.addresses',
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
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
                $proposal->rates()->create($rateData);
            }

            return $proposal;
        });

        return response()->json(['success' => true, 'data' => $proposal->load('rates')], 201);
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
                $proposal->rates()->create($rateData);
            }
        });

        return response()->json(['success' => true, 'data' => $proposal->load('rates')]);
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

        if ($proposal->status !== ClientProposal::STATUS_PENDING) {
            return response()->json(['success' => false, 'message' => 'Only pending proposals can be approved.'], 422);
        }

        $validated = $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);

        $proposal->update([
            'status' => ClientProposal::STATUS_APPROVED,
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'decision_remarks' => $validated['remarks'] ?? null,
        ]);

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    public function disapprove(Request $request, ClientProposal $proposal)
    {
        if (! $proposal->canBeApprovedBy($request->user())) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to disapprove proposals.'], 403);
        }

        if ($proposal->status !== ClientProposal::STATUS_PENDING) {
            return response()->json(['success' => false, 'message' => 'Only pending proposals can be disapproved.'], 422);
        }

        $validated = $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);

        $proposal->update([
            'status' => ClientProposal::STATUS_DISAPPROVED,
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'decision_remarks' => $validated['remarks'] ?? null,
        ]);

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

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    /**
     * Uploading the signed copy is what promotes an APPROVED proposal to ACCEPTED.
     */
    public function attachSigned(Request $request, ClientProposal $proposal)
    {
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

        return response()->json(['success' => true, 'data' => $proposal]);
    }

    public function downloadPdf(ClientProposal $proposal)
    {
        $proposal->load([
            'client',
            'client.addresses',
            'creator',
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
        ]);

        $pdf = Pdf::loadView('pdf.client-proposal', ['proposal' => $proposal]);

        return $pdf->download($proposal->code.'.pdf');
    }

    protected function rateRules(): array
    {
        return [
            'rates' => ['required', 'array', 'min:1'],
            'rates.*.origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'rates.*.destination_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'rates.*.container_id' => ['required', 'integer', 'exists:containers,id'],
            'rates.*.container_class_id' => ['required', 'integer', 'exists:container_class,id'],
            'rates.*.container_size_id' => ['required', 'integer', 'exists:container_size,id'],
            'rates.*.container_variant_id' => ['required', 'integer', 'exists:container_variants,id'],
            'rates.*.base_rate' => ['required', 'numeric', 'min:0'],
            'rates.*.discount_type' => ['nullable', 'in:percentage,fixed'],
            'rates.*.discount_value' => ['nullable', 'numeric', 'min:0'],
            'rates.*.final_rate' => ['required', 'numeric', 'min:0'],
        ];
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
            'status' => ClientProposal::STATUS_PENDING,
            'created_by' => $request->user()?->id,
        ]);
    }

    public function indexByLead(Request $request, $leadUuid)
    {
        $lead = CrmLead::where('uuid', $leadUuid)->firstOrFail();

        $proposals = ClientProposal::with([
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'creator:id,name',
            'decidedBy:id,name',
        ])->where('lead_id', $lead->id)
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 5))
            ->appends($request->query());

        return response()->json(['success' => true, 'data' => $proposals]);
    }

    public function storeForLead(Request $request, $leadUuid)
    {
        $lead = CrmLead::where('uuid', $leadUuid)->firstOrFail();
        $validated = $request->validate($this->rateRules());

        $proposal = DB::transaction(function () use ($lead, $validated, $request) {
            $yearMonth = Carbon::now()->format('Ym');
            $last = ClientProposal::where('code', 'like', "CPR-{$yearMonth}%")
                ->orderByDesc('id')->lockForUpdate()->first();
            $seq = $last ? ((int) substr($last->code, -4)) + 1 : 1;

            $proposal = ClientProposal::create([
                'uuid' => (string) Str::uuid(),
                'code' => sprintf('CPR-%s-%04d', $yearMonth, $seq),
                'lead_id' => $lead->id,
                'client_id' => null,
                'status' => ClientProposal::STATUS_PENDING,
                'created_by' => $request->user()?->id,
            ]);

            foreach ($validated['rates'] as $rateData) {
                $proposal->rates()->create($rateData);
            }

            return $proposal;
        });

        return response()->json(['success' => true, 'data' => $proposal->load('rates')], 201);
    }

    /**
     * Builds pre-filled (best-effort) proposal rate rows from the lead's
     * own container requirements (crm_lead_containers), so the "New Proposal"
     * form opens already populated. The frontend still lets the user edit,
     * add, or remove any row before saving.
     *
     * Only CV/FR/RF resolve fully (they carry class+size); LC/RC come back
     * with ports filled and container/class/size left null for manual pick,
     * since those types don't carry a class/size on the lead form.
     */
    public function leadContainerDefaults($leadUuid)
    {
        $lead = CrmLead::where('uuid', $leadUuid)->firstOrFail();

        $rows = $lead->containers()
            ->with(['originPort', 'destinationPort', 'containerClass', 'containerSize'])
            ->get()
            ->map(function ($lc) {
                $container = Container::where('code', $lc->container_type)->first();
                $variant = null;
                $baseRate = null;

                if ($container && $lc->container_class_id && $lc->container_size_id) {
                    $variant = ContainerVariant::where('container_id', $container->id)
                        ->where('container_class_id', $lc->container_class_id)
                        ->where('container_size_id', $lc->container_size_id)
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
