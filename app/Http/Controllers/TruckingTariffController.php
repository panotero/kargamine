<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesVersionedRates;
use App\Models\TruckingTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TruckingTariffController extends Controller
{
    use ManagesVersionedRates;

    public function index(Request $request)
    {
        $tariffs = TruckingTariff::query()
            ->with([
                'serviceableArea.port:port_id,code,name',
                'deliveryType:delivery_type_id,code,name'
            ])
            ->when($request->filled('area_id'), fn($q) => $q->where('area_id', $request->area_id))
            ->when($request->filled('delivery_type_id'), fn($q) => $q->where('delivery_type_id', $request->delivery_type_id))
            ->orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $tariffs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => ['required', 'integer', 'exists:serviceable_areas,area_id'],
            'delivery_type_id' => ['required', 'integer', 'exists:delivery_types,delivery_type_id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        $tariff = DB::transaction(function () use ($validated) {
            $this->closePreviousVersion(
                TruckingTariff::class,
                [
                    'area_id' => $validated['area_id'],
                    'delivery_type_id' => $validated['delivery_type_id'],
                ],
                $validated['effective_date']
            );

            return TruckingTariff::create($validated + ['is_active' => true]);
        });

        return response()->json([
            'success' => true,
            'data' => $tariff,
        ], 201);
    }

    public function show(TruckingTariff $truckingTariff)
    {
        return response()->json([
            'success' => true,
            'data' => $truckingTariff->load([
                'serviceableArea',
                'deliveryType',
            ]),
        ]);
    }

    public function update(Request $request, TruckingTariff $truckingTariff)
    {
        $validated = $request->validate([
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $truckingTariff->update($validated);

        return response()->json([
            'success' => true,
            'data' => $truckingTariff,
        ]);
    }

    public function destroy(TruckingTariff $truckingTariff)
    {
        $truckingTariff->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }
}
