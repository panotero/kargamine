<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::query()
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->withCount('ports')
            ->orderBy('name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:locations,name'],
            'is_active' => ['boolean'],
        ]);

        $location = Location::create($validated);

        return response()->json([
            'success' => true,
            'data' => $location,
        ], 201);
    }

    public function show(Location $location)
    {
        return response()->json([
            'success' => true,
            'data' => $location->load('ports'),
        ]);
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('locations', 'name')->ignore($location->location_id, 'location_id'),
            ],
            'is_active' => ['boolean'],
        ]);

        $location->update($validated);

        return response()->json([
            'success' => true,
            'data' => $location,
        ]);
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ], 200);
    }
}
