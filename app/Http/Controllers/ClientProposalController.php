<?php

namespace App\Http\Controllers;

use App\Models\ClientMaster;
use App\Models\ClientProposal;
use App\Models\ClientProposalRate;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\FileUploadService;

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
                    ->orWhereHas('client', fn($q) => $q->where('company_name', 'like', "%{$s}%"));
            })
            ->when($request->filled('status') && $request->status !== 'all', fn($q) => $q->where('status', $request->status))
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
            'creator',
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
        ]);

        $pdf = Pdf::loadView('pdf.client-proposal', ['proposal' => $proposal]);

        return $pdf->download($proposal->code . '.pdf');
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
}
