<?php

namespace App\Http\Controllers;

use App\Models\Lane;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaneController extends Controller
{
    public function index(Request $request)
    {
        $lanes = Lane::query()
            ->with([
                'originPort:port_id,code,name',
                'destinationPort:port_id,code,name'
            ])
            ->when($request->filled('origin_port_id'), fn($q) => $q->where('origin_port_id', $request->origin_port_id))
            ->when($request->filled('destination_port_id'), fn($q) => $q->where('destination_port_id', $request->destination_port_id))
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $lanes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin_port_id' => ['required', 'integer', 'exists:ports,port_id', 'different:destination_port_id'],
            'destination_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'is_active' => ['boolean'],
        ]);

        $lane = Lane::create($validated);

        return response()->json([
            'success' => true,
            'data' => $lane,
        ], 201);
    }

    public function show(Lane $lane)
    {
        return response()->json([
            'success' => true,
            'data' => $lane->load([
                'originPort',
                'destinationPort',
                'tariffRates' => fn($q) => $q->latest('effective_date'),
            ]),
        ]);
    }

    public function update(Request $request, Lane $lane)
    {
        $validated = $request->validate([
            'origin_port_id' => ['sometimes', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['sometimes', 'integer', 'exists:ports,port_id'],
            'is_active' => ['boolean'],
        ]);

        $lane->update($validated);

        return response()->json([
            'success' => true,
            'data' => $lane,
        ]);
    }

    public function destroy(Lane $lane)
    {
        $lane->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }
}
