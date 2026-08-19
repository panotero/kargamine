<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::query()
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->withCount(['ports', 'serviceableAreas'])
            ->orderBy('name')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);

        $location = DB::transaction(function () use ($data) {
            $location = Location::create([
                'name' => $data['name'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Areas first - a dropped port's own restrictOnDelete FK would
            // otherwise be blocked by a serviceable area still pointing at
            // it that's being dropped in this same request.
            $location->syncServiceableAreas($data['serviceable_areas'] ?? []);
            $location->syncPorts($data['ports'] ?? []);

            return $location;
        });

        $location->load(['ports', 'serviceableAreas']);

        return response()->json([
            'success' => true,
            'data' => $location,
        ], 201);
    }

    public function show(Location $location)
    {
        return response()->json([
            'success' => true,
            'data' => $location->load(['ports', 'serviceableAreas']),
        ]);
    }

    public function update(Request $request, Location $location)
    {
        $data = $this->validatePayload($request, $location);

        try {
            DB::transaction(function () use ($data, $location) {
                $location->fill(array_filter([
                    'name' => $data['name'] ?? null,
                ], fn ($v) => $v !== null));

                if (array_key_exists('is_active', $data)) {
                    $location->is_active = $data['is_active'];
                }

                $location->save();

                // Full replace when supplied. A removed port/area takes its
                // port charges/handling fees/trucking tariffs with it, but
                // still fails here (restrictOnDelete) if it's tied to a lane,
                // booking, proposal/contract line, voyage, or container asset -
                // that's intentional, it protects existing history. Areas
                // first, so a serviceable area being dropped alongside its
                // port doesn't itself block the port's deletion below.
                if (array_key_exists('serviceable_areas', $data)) {
                    $location->syncServiceableAreas($data['serviceable_areas']);
                }

                if (array_key_exists('ports', $data)) {
                    $location->syncPorts($data['ports']);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to remove one or more of those ports/serviceable areas - they still have lanes, bookings, proposals, contracts, or vessel voyages tied to them. Keep those entries (or clear out the related records first) and try again.',
                ], 422);
            }

            throw $e;
        }

        $location->load(['ports', 'serviceableAreas']);

        return response()->json([
            'success' => true,
            'data' => $location,
        ]);
    }

    protected function validatePayload(Request $request, ?Location $location = null): array
    {
        return $request->validate([
            'name' => [
                $location ? 'sometimes' : 'required',
                'string',
                'max:100',
                $location
                    ? Rule::unique('locations', 'name')->ignore($location->location_id, 'location_id')
                    : Rule::unique('locations', 'name'),
            ],
            'is_active' => ['sometimes', 'boolean'],

            'ports' => ['sometimes', 'array'],
            'ports.*.port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'ports.*.name' => ['required', 'string', 'max:100'],
            'ports.*.is_active' => ['sometimes', 'boolean'],

            'serviceable_areas' => ['sometimes', 'array'],
            'serviceable_areas.*.area_id' => ['nullable', 'integer', 'exists:serviceable_areas,area_id'],
            'serviceable_areas.*.area_name' => ['required', 'string', 'max:150'],
            'serviceable_areas.*.is_active' => ['sometimes', 'boolean'],
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
