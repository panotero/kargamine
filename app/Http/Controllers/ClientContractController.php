<?php

namespace App\Http\Controllers;

use App\Models\ClientContract;
use App\Models\ClientMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientContractController extends Controller
{
    public function index($clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();

        $contracts = ClientContract::with('rates', 'proposal:id,code')
            ->where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $contracts]);
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
