<?php

namespace App\Http\Controllers;

use App\Models\CharterService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CharterServiceController extends Controller
{
    public function index(Request $request)
    {
        $charterServices = CharterService::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $charterServices,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:charter_services,name'],
            'is_active' => ['boolean'],
        ]);

        $charterService = CharterService::create([
            'name' => $validated['name'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $charterService,
        ], 201);
    }

    public function show(CharterService $charterService)
    {
        return response()->json([
            'success' => true,
            'data' => $charterService,
        ]);
    }

    public function update(Request $request, CharterService $charterService)
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('charter_services', 'name')->ignore($charterService->charter_service_id, 'charter_service_id'),
            ],
            'is_active' => ['boolean'],
        ]);

        $charterService->update($validated);

        return response()->json([
            'success' => true,
            'data' => $charterService,
        ]);
    }

    public function destroy(CharterService $charterService)
    {
        $charterService->delete();

        return response()->json([
            'success' => true,
            'data' => null,
        ], 200);
    }
}
