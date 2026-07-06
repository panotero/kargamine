<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesVersionedRates;
use App\Models\LaneTariffRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaneTariffRateController extends Controller
{
    use ManagesVersionedRates;

    public function index(Request $request)
    {
        $rates = LaneTariffRate::query()
            ->with('lane.originPort', 'lane.destinationPort')
            ->when($request->filled('lane_id'), fn($q) => $q->where('lane_id', $request->lane_id))
            ->orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $rates,
        ]);
    }

    /**
     * Add a new rate version for a lane. Automatically closes the
     * previously active version for that same lane.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lane_id' => ['required', 'integer', 'exists:lanes,lane_id'],
            'frt' => ['required', 'numeric', 'min:0'],
            'bsc' => ['required', 'numeric', 'min:0'],
            'ra' => ['required', 'numeric', 'min:0'],
            'gri' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        $rate = DB::transaction(function () use ($validated) {
            $this->closePreviousVersion(
                LaneTariffRate::class,
                ['lane_id' => $validated['lane_id']],
                $validated['effective_date']
            );

            return LaneTariffRate::create($validated + ['is_active' => true]);
        });

        return response()->json([
            'success' => true,
            'data' => $rate,
        ], 201);
    }

    public function show(LaneTariffRate $laneTariffRate)
    {
        return response()->json([
            'success' => true,
            'data' => $laneTariffRate->load('lane'),
        ]);
    }

    /**
     * Corrections to the currently active version only (typo fixes, etc).
     * Use store() to roll a genuinely new rate into effect.
     */
    public function update(Request $request, LaneTariffRate $laneTariffRate)
    {
        $validated = $request->validate([
            'frt' => ['sometimes', 'numeric', 'min:0'],
            'bsc' => ['sometimes', 'numeric', 'min:0'],
            'ra' => ['sometimes', 'numeric', 'min:0'],
            'gri' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $laneTariffRate->update($validated);

        return response()->json([
            'success' => true,
            'data' => $laneTariffRate,
        ]);
    }

    public function destroy(LaneTariffRate $laneTariffRate)
    {
        $laneTariffRate->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }
}
