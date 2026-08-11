<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesVersionedRates;
use App\Models\ChargeType;
use App\Models\PortCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PortChargeController extends Controller
{
    use ManagesVersionedRates;

    public function index(Request $request)
    {
        $charges = PortCharge::query()
            ->with([
                'port:port_id,location_id,name',
                'port.location:location_id,name',
                'chargeType:charge_type_id,code,name',
            ])
            ->when($request->filled('port_id'), fn($q) => $q->where('port_id', $request->port_id))
            ->when($request->filled('charge_type_id'), fn($q) => $q->where('charge_type_id', $request->charge_type_id))
            ->when($request->filled('search'), fn($q) => $q->where(function ($q) use ($request) {
                $q->whereHas('port', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                    ->orWhereHas('chargeType', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            }))
            ->orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $charges]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'charge_type_id' => [
                'required',
                'integer',
                Rule::exists('charge_types', 'charge_type_id')->where('applicable_to', ChargeType::APPLICABLE_PORT),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        $charge = DB::transaction(function () use ($validated) {
            $this->closePreviousVersion(
                PortCharge::class,
                ['port_id' => $validated['port_id'], 'charge_type_id' => $validated['charge_type_id']],
                $validated['effective_date']
            );

            return PortCharge::create($validated + ['is_active' => true]);
        });

        return response()->json(['success' => true, 'data' => $charge], 201);
    }

    public function show(PortCharge $portCharge)
    {
        return response()->json(['success' => true, 'data' => $portCharge->load(['port.location', 'chargeType'])]);
    }

    public function update(Request $request, PortCharge $portCharge)
    {
        $validated = $request->validate([
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $portCharge->update($validated);

        return response()->json(['success' => true, 'data' => $portCharge]);
    }

    public function destroy(PortCharge $portCharge)
    {
        $portCharge->delete();

        return response()->json(['success' => true, 'data' => null]);
    }
}
