<?php

namespace App\Http\Controllers;

use App\Models\ClientContract;
use App\Models\ClientMaster;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientContractController extends Controller
{
    /**
     * Contracts for ONE client - used inside the Client Master detail modal.
     */
    public function index($clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();

        $contracts = ClientContract::with('rates', 'proposal:id,code')
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
        $contracts = ClientContract::with([
            'client:id,uuid,company_name,customer_code',
            'proposal:id,code',
        ])
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('client', fn ($q) => $q->where('company_name', 'like', "%{$s}%"))
                    ->orWhereHas('proposal', fn ($q) => $q->where('code', 'like', "%{$s}%"));
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

        $allContracts = ClientContract::all();

        $statusCounts = $allContracts->groupBy('status')->map(fn ($group) => $group->count());

        $expiring = ClientContract::where('status', ClientContract::STATUS_ACTIVE)
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
        $contract->load([
            'client',
            'proposal',
            'creator',
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
        ]);

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

        $pdf = Pdf::loadView('pdf.client-contract', ['contract' => $contract]);

        return $pdf->download($contract->code.'.pdf');
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
            'rates.*.base_rate' => ['required', 'numeric', 'min:0'],
            'rates.*.discount_type' => ['nullable', 'in:percentage,fixed'],
            'rates.*.discount_value' => ['nullable', 'numeric', 'min:0'],
            'rates.*.final_rate' => ['required', 'numeric', 'min:0'],
        ]);

        $contract = DB::transaction(function () use ($client, $validated, $request) {
            $yearMonth = Carbon::now()->format('Ym');
            $last = ClientContract::where('code', 'like', "CCT-{$yearMonth}%")
                ->orderByDesc('id')->lockForUpdate()->first();
            $seq = $last ? ((int) substr($last->code, -4)) + 1 : 1;

            $contract = ClientContract::create([
                'uuid' => (string) Str::uuid(),
                'code' => sprintf('CCT-%s-%04d', $yearMonth, $seq),
                'client_id' => $client->id,
                'client_proposal_id' => $validated['client_proposal_id'] ?? null,
                'signed_date' => $validated['signed_date'] ?? null,
                'valid_from' => $validated['valid_from'],
                'valid_to' => $validated['valid_to'],
                'status' => ClientContract::STATUS_ACTIVE,
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
}
