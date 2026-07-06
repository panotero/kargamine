<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesVersionedRates;
use App\Models\HandlingFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HandlingFeeController extends Controller
{
    use ManagesVersionedRates;

    public function index(Request $request)
    {
        $fees = HandlingFee::query()
            ->with('port:port_id,code,name')
            ->when($request->filled('port_id'), fn($q) => $q->where('port_id', $request->port_id))
            ->orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $fees,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        $fee = DB::transaction(function () use ($validated) {
            $this->closePreviousVersion(
                HandlingFee::class,
                ['port_id' => $validated['port_id']],
                $validated['effective_date']
            );

            return HandlingFee::create($validated + ['is_active' => true]);
        });

        return response()->json([
            'success' => true,
            'data' => $fee,
        ], 201);
    }

    public function show(HandlingFee $handlingFee)
    {
        return response()->json([
            'success' => true,
            'data' => $handlingFee->load('port'),
        ]);
    }

    public function update(Request $request, HandlingFee $handlingFee)
    {
        $validated = $request->validate([
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $handlingFee->update($validated);

        return response()->json([
            'success' => true,
            'data' => $handlingFee,
        ]);
    }

    public function destroy(HandlingFee $handlingFee)
    {
        $handlingFee->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }
}
