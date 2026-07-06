<?php

namespace App\Http\Controllers;

use App\Models\DeliveryType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryTypeController extends Controller
{
    public function index(Request $request)
    {
        $deliveryTypes = DeliveryType::orderBy('name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $deliveryTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:5', 'unique:delivery_types,code'],
            'name' => ['required', 'string', 'max:50'],
            'includes_origin_trucking' => ['boolean'],
            'includes_destination_trucking' => ['boolean'],
        ]);

        $deliveryType = DeliveryType::create($validated);

        return response()->json([
            'success' => true,
            'data' => $deliveryType,
        ], 201);
    }

    public function show(DeliveryType $deliveryType)
    {
        return response()->json([
            'success' => true,
            'data' => $deliveryType,
        ]);
    }

    public function update(Request $request, DeliveryType $deliveryType)
    {
        $validated = $request->validate([
            'code' => [
                'sometimes',
                'string',
                'max:5',
                Rule::unique('delivery_types', 'code')->ignore($deliveryType->delivery_type_id, 'delivery_type_id'),
            ],
            'name' => ['sometimes', 'string', 'max:50'],
            'includes_origin_trucking' => ['boolean'],
            'includes_destination_trucking' => ['boolean'],
        ]);

        $deliveryType->update($validated);

        return response()->json([
            'success' => true,
            'data' => $deliveryType,
        ]);
    }

    public function destroy(DeliveryType $deliveryType)
    {
        $deliveryType->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }
}
