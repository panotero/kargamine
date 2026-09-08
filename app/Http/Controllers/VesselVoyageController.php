<?php

namespace App\Http\Controllers;

use App\Models\BookingContainerUnit;
use App\Models\Vessel;
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
            ->with(['vessel', 'originPort.location', 'destinationPort.location'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->whereHas('vessel', fn ($v) => $v->where('name', 'like', "%{$request->search}%"))
                    ->orWhere('voyage_mnemonic', 'like', "%{$request->search}%");
            }))
            ->when($request->boolean('active_vessels_only'), fn ($q) => $q->whereHas('vessel', fn ($v) => $v->where('status', Vessel::STATUS_ACTIVE)))
            ->when($request->filled('vessel_id'), fn ($q) => $q->where('vessel_id', $request->vessel_id))
            ->when($request->filled('voyage_mnemonic'), fn ($q) => $q->where('voyage_mnemonic', 'like', "%{$request->voyage_mnemonic}%"))
            ->when($request->filled('vessel_name'), fn ($q) => $q->whereHas('vessel', fn ($v) => $v->where('name', 'like', "%{$request->vessel_name}%")))
            ->when($request->filled('departure_date'), fn ($q) => $q->whereDate('estimated_departure_at', $request->departure_date))
            ->when($request->filled('arrival_date'), fn ($q) => $q->whereDate('estimated_arrival_at', $request->arrival_date))
            ->when($request->filled('origin_port_id'), fn ($q) => $q->where('origin_port_id', $request->origin_port_id))
            ->when($request->filled('destination_port_id'), fn ($q) => $q->where('destination_port_id', $request->destination_port_id))
            ->orderBy('voyage_leg')
            ->orderByDesc('id')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $voyages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vessel_id' => ['required', 'integer', 'exists:vessels,id'],
            'voyage_mnemonic' => ['required', 'string', 'max:255', 'unique:vessel_voyages,voyage_mnemonic'],
            'voyage_leg' => ['required', 'string', 'max:10'],
            'origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['required', 'integer', 'exists:ports,port_id', 'different:origin_port_id'],
            'estimated_departure_at' => ['nullable', 'date'],
            'estimated_arrival_at' => ['nullable', 'date', 'after_or_equal:estimated_departure_at'],
        ]);

        $voyage = VesselVoyage::create($validated);

        return response()->json(['success' => true, 'data' => $voyage->load('vessel', 'originPort.location', 'destinationPort.location')], 201);
    }

    public function show(VesselVoyage $vesselVoyage)
    {
        return response()->json([
            'success' => true,
            'data' => $vesselVoyage->load('vessel', 'originPort.location', 'destinationPort.location'),
        ]);
    }

    public function update(Request $request, VesselVoyage $vesselVoyage)
    {
        $validated = $request->validate([
            'vessel_id' => ['sometimes', 'integer', 'exists:vessels,id'],
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

        return response()->json(['success' => true, 'data' => $vesselVoyage->load('vessel', 'originPort.location', 'destinationPort.location')]);
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
        $vesselVoyage->load(['vessel', 'originPort.location', 'destinationPort.location']);

        $units = BookingContainerUnit::where('vessel_voyage_id', $vesselVoyage->id)
            ->with([
                'containerAsset',
                'booking.client',
                'booking.billOfLading',
                'bookingLine.container',
                'bookingLine.containerClass',
                'bookingLine.containerSize',
                'relayPort.location',
            ])
            ->orderBy('booking_id')
            ->get();

        $pdf = Pdf::loadView('pdf.loadlist', [
            'voyage' => $vesselVoyage,
            'units' => $units,
        ]);

        return $pdf->download('loadlist-'.Str::slug($vesselVoyage->voyage_mnemonic).'.pdf');
    }

    /**
     * All legs of a vessel's current schedule with their assigned
     * containers attached, grouped per leg - powers the "Containers on
     * This Voyage" section of the Manage Voyage Schedule modal.
     */
    public function containersForVessel(Vessel $vessel)
    {
        $legs = VesselVoyage::where('vessel_id', $vessel->id)
            ->with(['originPort.location', 'destinationPort.location'])
            ->orderBy('voyage_leg')
            ->get();

        $units = BookingContainerUnit::whereIn('vessel_voyage_id', $legs->pluck('id'))
            ->with([
                'containerAsset',
                'booking.client',
                'bookingLine.container',
                'bookingLine.containerClass',
                'bookingLine.containerSize',
                'relayPort.location',
            ])
            ->get()
            ->groupBy('vessel_voyage_id');

        $legs->each(fn ($leg) => $leg->setRelation('units', $units->get($leg->id, collect())->values()));

        return response()->json(['success' => true, 'data' => $legs]);
    }

    /**
     * Consolidated manifest covering every leg of a vessel's schedule in
     * one PDF - each leg gets its own load list (units boarding at the
     * leg's origin) and unload list (units coming off at the leg's
     * destination, tagged Relay when they're being transferred onward
     * rather than finally discharged there), per SOP Step 11.
     */
    public function manifest(Vessel $vessel)
    {
        $legs = VesselVoyage::where('vessel_id', $vessel->id)
            ->with(['originPort.location', 'destinationPort.location'])
            ->orderBy('voyage_leg')
            ->get();

        $units = BookingContainerUnit::whereIn('vessel_voyage_id', $legs->pluck('id'))
            ->with([
                'containerAsset',
                'booking.client',
                'booking.billOfLading',
                'bookingLine.container',
                'bookingLine.containerClass',
                'bookingLine.containerSize',
                'relayPort.location',
            ])
            ->orderBy('booking_id')
            ->get()
            ->groupBy('vessel_voyage_id');

        $legs->each(fn ($leg) => $leg->setRelation('units', $units->get($leg->id, collect())->values()));

        $pdf = Pdf::loadView('pdf.voyage-manifest', [
            'vessel' => $vessel,
            'legs' => $legs,
        ]);

        return $pdf->download('voyage-manifest-'.Str::slug($vessel->name).'.pdf');
    }
}
