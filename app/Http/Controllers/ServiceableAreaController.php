<?php

namespace App\Http\Controllers;

use App\Models\ServiceableArea;
use Illuminate\Http\Request;

class ServiceableAreaController extends Controller
{
    public function index(Request $request)
    {
        $areas = ServiceableArea::query()
            ->with('port:port_id,code,name')
            ->when($request->filled('port_id'), fn($q) => $q->where('port_id', $request->port_id))
            ->when($request->filled('search'), fn($q) => $q->where('area_name', 'like', "%{$request->search}%"))
            ->orderBy('area_name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $areas,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'area_name' => ['required', 'string', 'max:150'],
            'is_active' => ['boolean'],
        ]);

        $area = ServiceableArea::create($validated);

        return response()->json([
            'success' => true,
            'data' => $area,
        ], 201);
    }

    public function show(ServiceableArea $serviceableArea)
    {
        return response()->json([
            'success' => true,
            'data' => $serviceableArea->load('port'),
        ]);
    }

    public function update(Request $request, ServiceableArea $serviceableArea)
    {
        $validated = $request->validate([
            'port_id' => ['sometimes', 'integer', 'exists:ports,port_id'],
            'area_name' => ['sometimes', 'string', 'max:150'],
            'is_active' => ['boolean'],
        ]);

        $serviceableArea->update($validated);

        return response()->json([
            'success' => true,
            'data' => $serviceableArea,
        ]);
    }

    public function destroy(ServiceableArea $serviceableArea)
    {
        $serviceableArea->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }
}
