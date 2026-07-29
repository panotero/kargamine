<?php

namespace App\Http\Controllers;

use App\Models\BookingContainerUnit;
use App\Models\VesselVoyage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/** SOP Step 10 (Voyage Plan) - master data: one row per vessel leg. */
class VesselVoyageController extends Controller
{
    public function index(Request $request)
    {
        $voyages = VesselVoyage::query()
            ->with(['originPort', 'destinationPort'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('vessel_name', 'like', "%{$request->search}%")
                    ->orWhere('voyage_mnemonic', 'like', "%{$request->search}%");
            }))
            ->orderByDesc('id')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $voyages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vessel_name' => ['required', 'string', 'max:255'],
            'voyage_mnemonic' => ['required', 'string', 'max:255', 'unique:vessel_voyages,voyage_mnemonic'],
            'voyage_leg' => ['required', 'string', 'max:10'],
            'origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['required', 'integer', 'exists:ports,port_id', 'different:origin_port_id'],
            'estimated_departure_at' => ['nullable', 'date'],
            'estimated_arrival_at' => ['nullable', 'date', 'after_or_equal:estimated_departure_at'],
        ]);

        $voyage = VesselVoyage::create($validated);

        return response()->json(['success' => true, 'data' => $voyage->load('originPort', 'destinationPort')], 201);
    }

    public function show(VesselVoyage $vesselVoyage)
    {
        return response()->json([
            'success' => true,
            'data' => $vesselVoyage->load('originPort', 'destinationPort'),
        ]);
    }

    public function update(Request $request, VesselVoyage $vesselVoyage)
    {
        $validated = $request->validate([
            'vessel_name' => ['sometimes', 'string', 'max:255'],
            'voyage_mnemonic' => [
                'sometimes', 'string', 'max:255',
                Rule::unique('vessel_voyages', 'voyage_mnemonic')->ignore($vesselVoyage->id),
            ],
            'voyage_leg' => ['sometimes', 'string', 'max:10'],
            'origin_port_id' => ['sometimes', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['sometimes', 'integer', 'exists:ports,port_id', 'different:origin_port_id'],
            'estimated_departure_at' => ['nullable', 'date'],
            'estimated_arrival_at' => ['nullable', 'date', 'after_or_equal:estimated_departure_at'],
        ]);

        $vesselVoyage->update($validated);

        return response()->json(['success' => true, 'data' => $vesselVoyage->load('originPort', 'destinationPort')]);
    }

    public function destroy(VesselVoyage $vesselVoyage)
    {
        $vesselVoyage->delete();

        return response()->json(['success' => true, 'data' => null]);
    }

    /**
     * SOP Step 11: "Admin generates load list" - a per-voyage manifest of
     * every container assigned to this leg. Deliberately doesn't
     * duplicate "Admin generates Bill of Lading" from the same step -
     * each booking already has its own BOL (generated at Confirm time,
     * downloadable via BookingController::downloadBol); this manifest
     * just surfaces that BOL number per row so Admin has everything
     * needed for this voyage in one document instead of re-issuing a
     * second, redundant BOL concept at the voyage level.
     */
    public function loadlist(VesselVoyage $vesselVoyage)
    {
        $vesselVoyage->load(['originPort', 'destinationPort']);

        $units = BookingContainerUnit::where('vessel_voyage_id', $vesselVoyage->id)
            ->with([
                'containerAsset',
                'booking.client',
                'booking.billOfLading',
                'bookingLine.container',
                'bookingLine.containerClass',
                'bookingLine.containerSize',
                'relayPort',
            ])
            ->orderBy('booking_id')
            ->get();

        $pdf = Pdf::loadView('pdf.loadlist', [
            'voyage' => $vesselVoyage,
            'units' => $units,
        ]);

        return $pdf->download('loadlist-'.Str::slug($vesselVoyage->voyage_mnemonic).'.pdf');
    }
}
