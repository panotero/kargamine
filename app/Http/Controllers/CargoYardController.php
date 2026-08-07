<?php

namespace App\Http\Controllers;

use App\Models\CargoYard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CargoYardController extends Controller
{
    public function index(Request $request)
    {
        $cargoYards = CargoYard::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $cargoYards,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:cargo_yards,name'],
            'is_active' => ['boolean'],
        ]);

        $cargoYard = CargoYard::create($validated);

        return response()->json([
            'success' => true,
            'data' => $cargoYard,
        ], 201);
    }

    public function show(CargoYard $cargoYard)
    {
        return response()->json([
            'success' => true,
            'data' => $cargoYard,
        ]);
    }

    public function update(Request $request, CargoYard $cargoYard)
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('cargo_yards', 'name')->ignore($cargoYard->cargo_yard_id, 'cargo_yard_id'),
            ],
            'is_active' => ['boolean'],
        ]);

        $cargoYard->update($validated);

        return response()->json([
            'success' => true,
            'data' => $cargoYard,
        ]);
    }

    public function destroy(CargoYard $cargoYard)
    {
        $cargoYard->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ], 200);
    }
}
