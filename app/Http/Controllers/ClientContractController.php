<?php

namespace App\Http\Controllers;

use App\Models\ClientContract;
use App\Models\ClientMaster;
use App\Models\ClientProposal;
use App\Services\TeamService;
use App\Support\RoleHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientContractController extends Controller
{
    /**
     * Relations loaded on show() and the two new endpoints below, so every
     * "here's the contract" response has the same shape.
     */
    protected const DETAIL_RELATIONS = [
        'client',
        'proposal',
        'creator',
        'terminator',
        'rates.originPort',
        'rates.destinationPort',
        'rates.container',
        'rates.containerClass',
        'rates.containerSize',
        'rates.variant',
    ];

    /**
     * Contracts for ONE client - used inside the Client Master detail modal.
     */
    public function index($clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();

        $contracts = ClientContract::with([
            'proposal:id,code',
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
        ])
            ->where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $contracts]);
    }

    /**
     * ALL contracts across ALL clients - used by the Contracts page.
     */
    public function indexAll(Request $request)
    {
        // Team-scoped visibility, same rule as CRM leads/Proposals/Clients: a
        // member only sees their own clients' contracts, a team leader sees
        // their subtree, superadmin sees everything.
        $visibleUserIds = RoleHelper::hasAnyRole($request->user(), ['superadmin'])
            ? null
            : TeamService::accessibleUserIds($request->user())->all();

        $contracts = ClientContract::with([
            'client:id,uuid,company_name,customer_code',
            'proposal:id,code',
        ])
            ->visibleTo($visibleUserIds)
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('client', fn($q) => $q->where('company_name', 'like', "%{$s}%"))
                    ->orWhereHas('proposal', fn($q) => $q->where('code', 'like', "%{$s}%"));
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                switch (strtolower($request->status)) {
                    case 'draft':
                        $q->where('status', ClientContract::STATUS_DRAFT);
                        break;
                    case 'active':
                        $q->where('status', ClientContract::STATUS_ACTIVE);
                        break;
                    case 'expired':
                        $q->where('status', ClientContract::STATUS_EXPIRED);
                        break;
                    case 'terminated':
                        $q->where('status', ClientContract::STATUS_TERMINATED);
                        break;
                    case 'expiring':
                        $q->where('status', ClientContract::STATUS_ACTIVE)
                            ->whereDate('valid_to', '>=', Carbon::today())
                            ->whereDate('valid_to', '<=', Carbon::today()->addMonth());
                        break;
                    case 'all':
                    default:
                        break;
                }
            })
            ->latest('updated_at')
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        $allContracts = ClientContract::visibleTo($visibleUserIds)->get();

        $statusCounts = $allContracts->groupBy('status')->map(fn($group) => $group->count());

        $expiring = ClientContract::visibleTo($visibleUserIds)
            ->where('status', ClientContract::STATUS_ACTIVE)
            ->whereDate('valid_to', '>=', Carbon::today())
            ->whereDate('valid_to', '<=', Carbon::today()->addMonth())
            ->count();

        return response()->json([
            'success' => true,
            'data' => $contracts,
            'status_counts' => [
                'all' => $allContracts->count(),
                'draft' => $statusCounts->get(ClientContract::STATUS_DRAFT, 0),
                'active' => $statusCounts->get(ClientContract::STATUS_ACTIVE, 0),
                'expired' => $statusCounts->get(ClientContract::STATUS_EXPIRED, 0),
                'terminated' => $statusCounts->get(ClientContract::STATUS_TERMINATED, 0),
                'expiring' => $expiring,
            ],
        ]);
    }

    public function show(ClientContract $contract)
    {
        $contract->load(self::DETAIL_RELATIONS);

        return response()->json(['success' => true, 'data' => $contract]);
    }

    public function downloadPdf(ClientContract $contract)
    {
        $contract->load([
            'client',
            'client.addresses',
            'proposal',
            'creator',
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
        ]);

        $pdf = Pdf::loadView('pdf.clientContract', ['contract' => $contract]);

        return $pdf->download($contract->code . '.pdf');
    }

    public function store(Request $request, $clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();

        $validated = $request->validate([
            'client_proposal_id' => ['nullable', 'integer', 'exists:client_proposals,id'],
            'signed_date' => ['nullable', 'date'],
            'valid_from' => ['required', 'date'],
            'valid_to' => ['required', 'date', 'after_or_equal:valid_from'],
            'signed_document_path' => ['nullable', 'string'],
            'rates' => ['required', 'array', 'min:1'],
            'rates.*.origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'rates.*.destination_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'rates.*.container_id' => ['required', 'integer', 'exists:containers,id'],
            'rates.*.container_class_id' => ['required', 'integer', 'exists:container_class,id'],
            'rates.*.container_size_id' => ['required', 'integer', 'exists:container_size,id'],
            'rates.*.container_variant_id' => ['required', 'integer', 'exists:container_variants,id'],
            'rates.*.min_van_qty' => ['nullable', 'integer', 'min:1'],
            'rates.*.base_rate' => ['required', 'numeric', 'min:0'],
            'rates.*.discount_type' => ['nullable', 'in:percentage,fixed'],
            'rates.*.discount_value' => ['nullable', 'numeric', 'min:0'],
            'rates.*.final_rate' => ['required', 'numeric', 'min:0'],
        ]);

        $contract = DB::transaction(function () use ($client, $validated, $request) {
            $contract = ClientContract::create([
                'uuid' => (string) Str::uuid(),
                'code' => $this->nextContractCode(),
                'client_id' => $client->id,
                'client_proposal_id' => $validated['client_proposal_id'] ?? null,
                'signed_date' => $validated['signed_date'] ?? null,
                'valid_from' => $validated['valid_from'],
                'valid_to' => $validated['valid_to'],
                'status' => ClientContract::STATUS_DRAFT,
                'signed_document_path' => $validated['signed_document_path'] ?? null,
                'created_by' => $request->user()?->id,
            ]);

            foreach ($validated['rates'] as $rate) {
                $contract->rates()->create($rate);
            }

            return $contract;
        });

        return response()->json(['success' => true, 'data' => $contract->load('rates')], 201);
    }

    /**
     * CCT-{Ym}-0001, resetting each month. Shared by store() and
     * createFromProposal() so both creation paths generate codes the same
     * way and can never collide with each other.
     */
    protected function nextContractCode(): string
    {
        $yearMonth = Carbon::now()->format('Ym');
        $last = ClientContract::where('code', 'like', "CCT-{$yearMonth}%")
            ->orderByDesc('id')->lockForUpdate()->first();
        $seq = $last ? ((int) substr($last->code, -4)) + 1 : 1;

        return sprintf('CCT-%s-%04d', $yearMonth, $seq);
    }

    /**
     * Converts an Accepted proposal directly into an Active contract - the
     * only creation path reachable from the Proposals page. The client
     * master modal's own store() above is untouched and stays available
     * for that page's separate flow.
     */
    public function createFromProposal(Request $request, ClientProposal $proposal)
    {
        if ($proposal->status !== ClientProposal::STATUS_ACCEPTED) {
            return response()->json([
                'success' => false,
                'message' => 'Only an Accepted proposal can be converted to a contract.',
            ], 422);
        }

        if (! $proposal->client_id) {
            return response()->json([
                'success' => false,
                'message' => 'This proposal has no client assigned yet.',
            ], 422);
        }

        $hasActiveContract = ClientContract::where('client_proposal_id', $proposal->id)
            ->where('status', ClientContract::STATUS_ACTIVE)
            ->exists();

        if ($hasActiveContract) {
            return response()->json([
                'success' => false,
                'message' => 'This proposal already has an active contract.',
            ], 422);
        }

        $proposalRates = $proposal->rates()->get();

        if ($proposalRates->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'This proposal has no rate lines.',
            ], 422);
        }

        $validated = $request->validate([
            'signed_date' => ['nullable', 'date'],
            'valid_from' => ['required', 'date'],
            'valid_to' => ['required', 'date', 'after_or_equal:valid_from'],
            'rate_overrides' => ['sometimes', 'array'],
            'rate_overrides.*.min_van_qty' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'rate_overrides.*.base_rate' => ['sometimes', 'numeric', 'min:0'],
            'rate_overrides.*.discount_type' => ['sometimes', 'nullable', 'in:percentage,fixed'],
            'rate_overrides.*.discount_value' => ['sometimes', 'numeric', 'min:0'],
            'rate_overrides.*.final_rate' => ['sometimes', 'numeric', 'min:0'],
        ]);

        $overrides = $validated['rate_overrides'] ?? [];

        $contract = DB::transaction(function () use ($proposal, $validated, $overrides, $proposalRates, $request) {
            $contract = ClientContract::create([
                'uuid' => (string) Str::uuid(),
                'code' => $this->nextContractCode(),
                'client_id' => $proposal->client_id,
                'client_proposal_id' => $proposal->id,
                'signed_date' => $validated['signed_date'] ?? null,
                'valid_from' => $validated['valid_from'],
                'valid_to' => $validated['valid_to'],
                'status' => ClientContract::STATUS_DRAFT,
                'created_by' => $request->user()?->id,
            ]);

            foreach ($proposalRates as $proposalRate) {
                $override = $overrides[$proposalRate->id] ?? [];

                $contract->rates()->create([
                    'origin_port_id' => $proposalRate->origin_port_id,
                    'destination_port_id' => $proposalRate->destination_port_id,
                    'container_id' => $proposalRate->container_id,
                    'container_class_id' => $proposalRate->container_class_id,
                    'container_size_id' => $proposalRate->container_size_id,
                    'container_variant_id' => $proposalRate->container_variant_id,
                    'min_van_qty' => array_key_exists('min_van_qty', $override) ? $override['min_van_qty'] : $proposalRate->min_van_qty,
                    'base_rate' => $override['base_rate'] ?? $proposalRate->base_rate,
                    'discount_type' => array_key_exists('discount_type', $override) ? $override['discount_type'] : $proposalRate->discount_type,
                    'discount_value' => $override['discount_value'] ?? $proposalRate->discount_value,
                    'final_rate' => $override['final_rate'] ?? $proposalRate->final_rate,
                ]);
            }

            return $contract;
        });

        $contract->load(self::DETAIL_RELATIONS);

        return response()->json(['success' => true, 'data' => $contract], 201);
    }

    /**
     * Approval workflow - approve-only (no reject/disapprove), moving a
     * Draft contract to Active. Mirrors ClientProposalController::approve().
     */
    public function approve(Request $request, ClientContract $contract)
    {
        if (! $contract->canBeApprovedBy($request->user())) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to approve contracts.'], 403);
        }

        if ($contract->status !== ClientContract::STATUS_DRAFT) {
            return response()->json(['success' => false, 'message' => 'Only draft contracts can be approved.'], 422);
        }

        $validated = $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);

        $contract->update([
            'status' => ClientContract::STATUS_ACTIVE,
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'decision_remarks' => $validated['remarks'] ?? null,
        ]);

        $contract->load(self::DETAIL_RELATIONS);

        return response()->json(['success' => true, 'data' => $contract]);
    }

    public function terminate(Request $request, ClientContract $contract)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($contract->status !== ClientContract::STATUS_ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'Only an Active contract can be terminated.',
            ], 422);
        }

        $contract->update([
            'status' => ClientContract::STATUS_TERMINATED,
            'terminated_reason' => $validated['reason'],
            'terminated_by' => $request->user()?->id,
            'terminated_at' => now(),
        ]);

        $contract->load(self::DETAIL_RELATIONS);

        return response()->json(['success' => true, 'data' => $contract]);
    }
}
