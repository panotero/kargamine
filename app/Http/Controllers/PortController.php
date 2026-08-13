<?php

namespace App\Http\Controllers;

use App\Models\Port;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $ports = Port::query()
            ->with('location')
            ->when($request->filled('search'), fn($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhereHas('location', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            }))
            ->when($request->filled('location_id'), fn($q) => $q->where('location_id', $request->location_id))
            ->orderBy('name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $ports,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => ['required', 'integer', 'exists:locations,location_id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ports', 'name')->where(fn($q) => $q->where('location_id', $request->location_id)),
            ],
            'is_active' => ['boolean'],
        ]);

        $port = Port::create($validated);

        return response()->json([
            'success' => true,
            'data' => $port->load('location'),
        ], 201);
    }

    public function show(Port $port)
    {
        return response()->json([
            'success' => true,
            'data' => $port->load(['location.serviceableAreas']),
        ]);
    }

    public function update(Request $request, Port $port)
    {
        $locationId = $request->input('location_id', $port->location_id);

        $validated = $request->validate([
            'location_id' => ['sometimes', 'integer', 'exists:locations,location_id'],
            'name' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('ports', 'name')
                    ->where(fn($q) => $q->where('location_id', $locationId))
                    ->ignore($port->port_id, 'port_id'),
            ],
            'is_active' => ['boolean'],
        ]);

        $port->update($validated);

        return response()->json([
            'success' => true,
            'data' => $port->load('location'),
        ]);
    }

    public function destroy(Port $port)
    {
        $port->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ], 200);
    }
}
