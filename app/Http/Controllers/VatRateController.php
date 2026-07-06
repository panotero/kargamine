<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesVersionedRates;
use App\Models\VatRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VatRateController extends Controller
{
    use ManagesVersionedRates;

    public function index(Request $request)
    {
        $rates = VatRate::orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $rates,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'effective_date' => ['required', 'date'],
        ]);

        $rate = DB::transaction(function () use ($validated) {
            // VAT is a single global setting - no scoping columns needed.
            $this->closePreviousVersion(VatRate::class, [], $validated['effective_date']);

            return VatRate::create($validated + ['is_active' => true]);
        });

        return response()->json([
            'success' => true,
            'data' => $rate,
        ], 201);
    }

    public function show(VatRate $vatRate)
    {
        return response()->json([
            'success' => true,
            'data' => $vatRate,
        ]);
    }

    public function update(Request $request, VatRate $vatRate)
    {
        $validated = $request->validate([
            'rate_percent' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $vatRate->update($validated);

        return response()->json([
            'success' => true,
            'data' => $vatRate,
        ]);
    }

    public function destroy(VatRate $vatRate)
    {
        $vatRate->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }
}
