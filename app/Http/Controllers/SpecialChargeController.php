<?php

namespace App\Http\Controllers;

use App\Models\SpecialCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SpecialChargeController extends Controller
{
    public function index(Request $request)
    {
        $specialCharges = SpecialCharge::query()
            ->with('rates')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
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
            'is_active' => ['boolean'],
            'rates' => ['required', 'array', 'min:1'],
            'rates.*.unit' => ['required', 'string', 'max:50'],
            'rates.*.base_value' => ['required', 'numeric', 'min:0'],
        ]);

        $this->rejectDuplicateUnits($validated['rates']);

        $specialCharge = DB::transaction(function () use ($validated) {
            $specialCharge = SpecialCharge::create([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            foreach ($validated['rates'] as $rateData) {
                $specialCharge->rates()->create([
                    'unit' => $rateData['unit'],
                    'base_value' => $rateData['base_value'],
                ]);
            }

            return $specialCharge;
        });

        return response()->json([
            'success' => true,
            'data' => $specialCharge->load('rates'),
        ], 201);
    }

    public function show(SpecialCharge $specialCharge)
    {
        return response()->json([
            'success' => true,
            'data' => $specialCharge->load('rates'),
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
            'is_active' => ['boolean'],
            'rates' => ['required', 'array', 'min:1'],
            'rates.*.id' => ['nullable', 'integer'],
            'rates.*.unit' => ['required', 'string', 'max:50'],
            'rates.*.base_value' => ['required', 'numeric', 'min:0'],
        ]);

        $this->rejectDuplicateUnits($validated['rates']);

        DB::transaction(function () use ($validated, $specialCharge) {
            $specialCharge->update(array_filter([
                'name' => $validated['name'] ?? null,
                'is_active' => $validated['is_active'] ?? null,
            ], fn ($v) => ! is_null($v)));

            $specialCharge->syncRates($validated['rates']);
        });

        return response()->json([
            'success' => true,
            'data' => $specialCharge->load('rates'),
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

    /**
     * Reject a payload that lists the same unit more than once.
     *
     * @param  array<int, array{id?: int|null, unit: string, base_value: float}>  $rates
     */
    protected function rejectDuplicateUnits(array $rates): void
    {
        $units = array_map(fn ($rate) => $rate['unit'], $rates);

        if (count($units) !== count(array_unique($units))) {
            abort(response()->json([
                'success' => false,
                'message' => 'Each unit may only be listed once per special charge.',
            ], 422));
        }
    }
}
