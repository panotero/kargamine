<?php

namespace App\Http\Controllers;

use App\Models\ClientMaster;
use App\Models\ClientProposal;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientProposalController extends Controller
{
    public function index($clientUuid)
    {
        $client = ClientMaster::where('uuid', $clientUuid)->firstOrFail();

        $proposals = ClientProposal::with([
            'rates.originPort',
            'rates.destinationPort',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'creator:id,name',
        ])->where('client_id', $client->id)->orderByDesc('created_at')->get();

        return response()->json(['success' => true, 'data' => $proposals]);
    }

    /**
     * Looks up the FRT for a container/lane combination straight from the
     * tariff engine (Lane -> active LaneTariffRate -> LaneTariffRatePrice).
     */
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
            ->activeOn()
            ->orderByDesc('effective_date')
            ->first();

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

        $validated = $request->validate([
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

        $proposal = DB::transaction(function () use ($client, $validated, $request) {
            $yearMonth = Carbon::now()->format('Ym');
            $last = ClientProposal::where('code', 'like', "CPR-{$yearMonth}%")
                ->orderByDesc('id')->lockForUpdate()->first();
            $seq = $last ? ((int) substr($last->code, -4)) + 1 : 1;

            $proposal = ClientProposal::create([
                'uuid' => (string) Str::uuid(),
                'code' => sprintf('CPR-%s-%04d', $yearMonth, $seq),
                'client_id' => $client->id,
                'status' => 1,
                'created_by' => $request->user()?->id,
            ]);

            foreach ($validated['rates'] as $rate) {
                $proposal->rates()->create($rate);
            }

            return $proposal;
        });

        return response()->json(['success' => true, 'data' => $proposal->load('rates')], 201);
    }
}
