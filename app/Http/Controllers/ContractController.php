<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Proposal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $contracts = Contract::query()
            ->with(['proposal:id,code', 'lead'])
            ->when($request->filled('lead_id'), fn($q) => $q->where('lead_id', $request->lead_id))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest('id')
            ->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $contracts]);
    }

    public function show(Contract $contract)
    {
        return response()->json(['success' => true, 'data' => $contract->load(['proposal', 'lead', 'rates'])]);
    }

    /**
     * Create a contract from an agreed proposal.
     *
     * Expects lead_id + proposal_id (the proposal the client signed off on),
     * the contract validity window, and one or more rate lines - each line
     * carries the lane/container combination plus the discount (percentage
     * or fixed) to apply to that lane's FRT at booking time.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'lead_id' => ['required', 'integer', 'exists:crm_leads,id'],
                'proposal_id' => ['required', 'integer', 'exists:proposals,id'],
                'signed_date' => ['nullable', 'date'],
                'valid_from' => ['required', 'date'],
                'valid_to' => ['required', 'date', 'after_or_equal:valid_from'],
                'signed_document_path' => ['nullable', 'string'],

                'rates' => ['required', 'array', 'min:1'],
                'rates.*.route_from' => ['required'],
                'rates.*.route_to' => ['required'],
                'rates.*.min_van_qty' => ['required', 'integer', 'min:1'],
                'rates.*.container_class' => ['required'],
                'rates.*.container_type' => ['required'],
                'rates.*.container_size' => ['required'],
                'rates.*.origin_service_type' => ['required'],
                'rates.*.destination_service_type' => ['required'],
                'rates.*.discount_type' => ['required', 'in:PERCENTAGE,FIXED'],
                'rates.*.discount_value' => ['required', 'numeric', 'min:0'],
            ]);

            $proposal = Proposal::findOrFail($validated['proposal_id']);

            // Guard rail: the proposal must actually belong to the lead the
            // contract is being written for.
            if ((int) $proposal->lead_id !== (int) $validated['lead_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'This proposal does not belong to the selected lead.',
                ], 422);
            }

            DB::beginTransaction();

            // Auto-generate the contract code - same pattern as proposal
            // codes: CTR-{YYYYMM}-{0001}, incrementing per month.
            $now = Carbon::now();
            $yearMonth = $now->format('Ym');
            $prefix = 'CTR';

            $lastContract = Contract::where('code', 'like', "{$prefix}-{$yearMonth}%")
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if (! $lastContract) {
                $sequence = 1;
            } else {
                $lastSequence = (int) substr($lastContract->code, -4);
                $sequence = $lastSequence + 1;
            }

            $sequencePadded = str_pad($sequence, 4, '0', STR_PAD_LEFT);
            $code = "{$prefix}-{$yearMonth}-{$sequencePadded}";

            $contract = Contract::create([
                'uuid' => (string) Str::uuid(),
                'code' => $code,
                'proposal_id' => $validated['proposal_id'],
                'lead_id' => $validated['lead_id'],
                'signed_date' => $validated['signed_date'] ?? null,
                'valid_from' => $validated['valid_from'],
                'valid_to' => $validated['valid_to'],
                'status' => Contract::STATUS_ACTIVE,
                'signed_document_path' => $validated['signed_document_path'] ?? null,
                'created_by' => $request->user()?->id,
            ]);

            foreach ($validated['rates'] as $rate) {
                $contract->rates()->create($rate + ['is_active' => true]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $contract->load('rates'),
            ], 201);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    /**
     * Prefill helper: pulls the agreed proposal's rate lines so the contract
     * form can start from what was already proposed, instead of re-typing
     * every lane/container combination from scratch.
     */
    public function ratesFromProposal(Proposal $proposal)
    {
        $rates = $proposal->rates()->get([
            'route_from',
            'route_to',
            'min_van_qty',
            'container_class',
            'container_type',
            'container_size',
            'origin_service_type',
            'destination_service_type',
        ]);

        return response()->json(['success' => true, 'data' => $rates]);
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'valid_from' => ['sometimes', 'date'],
            'valid_to' => ['sometimes', 'date', 'after_or_equal:valid_from'],
            'status' => ['sometimes', 'integer', 'in:1,2,3,4'],
            'signed_date' => ['nullable', 'date'],
            'signed_document_path' => ['nullable', 'string'],
        ]);

        $contract->update($validated);

        return response()->json(['success' => true, 'data' => $contract]);
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return response()->json(['success' => true, 'data' => null]);
    }
}
