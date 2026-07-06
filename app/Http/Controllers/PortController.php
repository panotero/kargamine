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
            ->when($request->filled('search'), fn($q) => $q->where(function ($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%");
            }))
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
            'code' => ['required', 'string', 'max:10', 'unique:ports,code'],
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $port = Port::create($validated);

        return response()->json([
            'success' => true,
            'data' => $port,
        ], 201);
    }

    public function show(Port $port)
    {
        return response()->json([
            'success' => true,
            'data' => $port->load('serviceableAreas'),
        ]);
    }

    public function update(Request $request, Port $port)
    {
        $validated = $request->validate([
            'code' => [
                'sometimes',
                'string',
                'max:10',
                Rule::unique('ports', 'code')->ignore($port->port_id, 'port_id'),
            ],
            'name' => ['sometimes', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $port->update($validated);

        return response()->json([
            'success' => true,
            'data' => $port,
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
