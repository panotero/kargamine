<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use App\Models\VesselMaintenanceRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Vessel Management - fleet master data (specs), status tagging (Active /
 * Under Repair / Out of Service / Decommissioned) with a full transition
 * history, and a service/maintenance log. Feeds Voyage Schedule's
 * active-vessel picker via Vessel::STATUS_ACTIVE.
 */
class VesselController extends Controller
{
    public function index(Request $request)
    {
        $query = Vessel::query()
            ->with('homePort.location')
            ->withCount('voyages')
            ->withMax('maintenanceRecords as last_serviced_at', 'performed_at')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('vessel_code', 'like', "%{$request->search}%")
                    ->orWhere('imo_number', 'like', "%{$request->search}%");
            }))
            ->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', $request->status));

        $vessels = $query->orderBy('name')->paginate($request->get('per_page', 25));

        $statusCounts = ['all' => Vessel::count()];
        foreach (Vessel::STATUS_LABELS as $code => $label) {
            $statusCounts[$code] = Vessel::where('status', $code)->count();
        }

        return response()->json(['success' => true, 'data' => $vessels, 'status_counts' => $statusCounts]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $vessel = Vessel::create($validated + ['status' => $validated['status'] ?? Vessel::STATUS_ACTIVE]);

        $vessel->statusHistories()->create([
            'from_status' => null,
            'to_status' => $vessel->status,
            'notes' => 'Vessel added to fleet.',
            'recorded_by' => $request->user()?->id,
            'recorded_at' => now(),
        ]);

        return response()->json(['success' => true, 'data' => $vessel->load('homePort.location')], 201);
    }

    public function show(Vessel $vessel)
    {
        return response()->json([
            'success' => true,
            'data' => $vessel->load([
                'homePort.location',
                'statusHistories.recordedBy',
                'maintenanceRecords.recordedBy',
            ]),
        ]);
    }

    public function update(Request $request, Vessel $vessel)
    {
        $rules = $this->rules($vessel->id);
        unset($rules['status']);

        $validated = $request->validate($rules);

        $vessel->update($validated);

        return response()->json(['success' => true, 'data' => $vessel->load('homePort.location')]);
    }

    public function destroy(Vessel $vessel)
    {
        if ($vessel->voyages()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This vessel has voyage schedule records and cannot be deleted. Decommission it instead.',
            ], 422);
        }

        $vessel->delete();

        return response()->json(['success' => true, 'data' => null]);
    }

    public function changeStatus(Request $request, Vessel $vessel)
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', Rule::in(array_keys(Vessel::STATUS_LABELS))],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $vessel->applyStatusChange($validated['status'], $validated['notes'] ?? null, $request->user()?->id);

        return response()->json(['success' => true, 'data' => $vessel->fresh()->load('statusHistories.recordedBy')]);
    }

    public function storeMaintenanceRecord(Request $request, Vessel $vessel)
    {
        $validated = $request->validate([
            'maintenance_type' => ['required', 'string', 'max:255'],
            'performed_at' => ['required', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:performed_at'],
            'description' => ['nullable', 'string', 'max:2000'],
            'performed_by' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'next_due_at' => ['nullable', 'date'],
        ]);

        $record = $vessel->maintenanceRecords()->create($validated + ['recorded_by' => $request->user()?->id]);

        return response()->json(['success' => true, 'data' => $record->load('recordedBy')], 201);
    }

    public function destroyMaintenanceRecord(Vessel $vessel, VesselMaintenanceRecord $maintenanceRecord)
    {
        abort_if($maintenanceRecord->vessel_id !== $vessel->id, 404);

        $maintenanceRecord->delete();

        return response()->json(['success' => true, 'data' => null]);
    }

    protected function rules(?int $ignoreId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'vessel_code' => ['nullable', 'string', 'max:255', Rule::unique('vessels', 'vessel_code')->ignore($ignoreId)],
            'vessel_type' => ['nullable', 'string', 'max:255'],
            'imo_number' => ['nullable', 'string', 'max:255'],
            'call_sign' => ['nullable', 'string', 'max:255'],
            'mmsi_number' => ['nullable', 'string', 'max:255'],
            'flag_state' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', Rule::in(array_keys(Vessel::STATUS_LABELS))],
            'engine_count' => ['nullable', 'integer', 'min:0'],
            'engine_power_hp' => ['nullable', 'numeric', 'min:0'],
            'date_manufactured' => ['nullable', 'date'],
            'gross_tonnage' => ['nullable', 'numeric', 'min:0'],
            'deadweight_tonnage' => ['nullable', 'numeric', 'min:0'],
            'capacity_teu' => ['nullable', 'integer', 'min:0'],
            'length_overall_m' => ['nullable', 'numeric', 'min:0'],
            'beam_m' => ['nullable', 'numeric', 'min:0'],
            'draft_m' => ['nullable', 'numeric', 'min:0'],
            'max_speed_knots' => ['nullable', 'numeric', 'min:0'],
            'home_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'owner_operator' => ['nullable', 'string', 'max:255'],
            'classification_society' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
