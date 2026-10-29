<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesVersionedRates;
use App\Models\TruckingTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TruckingTariffController extends Controller
{
    use ManagesVersionedRates;

    public function index(Request $request)
    {
        $tariffs = TruckingTariff::query()
            ->with([
                'serviceableArea.location:location_id,name',
                'port:port_id,name',
            ])
            ->when($request->filled('area_id'), fn($q) => $q->where('area_id', $request->area_id))
            ->when($request->filled('port_id'), fn($q) => $q->where('port_id', $request->port_id))
            ->when($request->filled('search'), fn($q) => $q->whereHas('serviceableArea', function ($q) use ($request) {
                $q->where('area_name', 'like', "%{$request->search}%")
                    ->orWhereHas('location', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            }))
            ->orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $tariffs]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => [
                'required', 'integer', 'exists:serviceable_areas,area_id',
                // A trucking tariff is versioned (same idea as Lane Tariff
                // Rates/Port Charges) - re-adding the same Area+Port with a
                // new effective date is a legitimate new version (the old
                // one gets closed below), not a duplicate. Only the exact
                // same Area+Port+Date combo is blocked.
                Rule::unique('trucking_tariffs', 'area_id')->where(
                    fn ($query) => $query->where('port_id', $request->port_id)
                        ->where('effective_date', $request->effective_date)
                ),
            ],
            'port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ], [
            'area_id.unique' => 'A trucking tariff for this Serviceable Area and Port combination already exists on this effective date.',
        ]);

        $tariff = DB::transaction(function () use ($validated) {
            $this->closePreviousVersion(
                TruckingTariff::class,
                [
                    'area_id' => $validated['area_id'],
                    'port_id' => $validated['port_id'],
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
                'serviceableArea.location',
                'port',
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
