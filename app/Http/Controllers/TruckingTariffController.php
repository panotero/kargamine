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
                'serviceableArea.port:port_id,location_id,name',
                'serviceableArea.port.location:location_id,name',
                'deliveryType:delivery_type_id,code,name',
            ])
            ->when($request->filled('area_id'), fn($q) => $q->where('area_id', $request->area_id))
            ->when($request->filled('delivery_type_id'), fn($q) => $q->where('delivery_type_id', $request->delivery_type_id))
            ->when($request->filled('search'), fn($q) => $q->whereHas('serviceableArea', function ($q) use ($request) {
                $q->where('area_name', 'like', "%{$request->search}%")
                    ->orWhereHas('port', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            }))
            ->orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $tariffs]);
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
                'serviceableArea.port.location',
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
