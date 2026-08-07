<?php

namespace App\Http\Controllers;

use App\Models\SpecialCharge;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpecialChargeController extends Controller
{
    public function index(Request $request)
    {
        $specialCharges = SpecialCharge::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $specialCharges,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:special_charges,name'],
            'base_value' => ['nullable', 'numeric'],
            'is_active' => ['boolean'],
        ]);

        $specialCharge = SpecialCharge::create($validated);

        return response()->json([
            'success' => true,
            'data' => $specialCharge,
        ], 201);
    }

    public function show(SpecialCharge $specialCharge)
    {
        return response()->json([
            'success' => true,
            'data' => $specialCharge,
        ]);
    }

    public function update(Request $request, SpecialCharge $specialCharge)
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('special_charges', 'name')->ignore($specialCharge->special_charge_id, 'special_charge_id'),
            ],
            'base_value' => ['nullable', 'numeric'],
            'is_active' => ['boolean'],
        ]);

        $specialCharge->update($validated);

        return response()->json([
            'success' => true,
            'data' => $specialCharge,
        ]);
    }

    public function destroy(SpecialCharge $specialCharge)
    {
        $specialCharge->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ], 200);
    }
}
