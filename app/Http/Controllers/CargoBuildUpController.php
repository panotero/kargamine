<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

/**
 * The "Cargo Build-Up" status board from SOP Step 2 (Phase 2), extended
 * through Steps 3-4 (Phase 3: ATW/CAN + CV assignment), Steps 6-7
 * (Phase 4: Gate Pass scan-confirmation), Steps 5 & 9 (Phase 5: EIR
 * Out/In), and Steps 10-11 (Phase 6: Vessel Voyage assignment). All 13
 * buckets are computable now - the SOP Step 2-11 pipeline this dashboard
 * was built to track is complete.
 */
class CargoBuildUpController extends Controller
{
    public const TRACKED_BUCKETS = [
        'cargo_build_up', 'tentative', 'live', 'for_atw', 'for_can', 'for_cv_assignment',
        'for_documentation', 'for_gate_out', 'pickup_in_transit', 'partial_in_yard', 'in_yard',
        'for_vessel_loading', 'shut_out',
    ];

    /**
     * "Cargo Build-Up" itself is documented in the SOP as the umbrella
     * total (tentative + live combined) that the CSR view then splits
     * into Tentative vs Live - not a fourth, independently-filtered set.
     */
    public const BUCKETS = [
        'cargo_build_up' => [
            'label' => 'Cargo Build-Up',
            'description' => 'All bookings with at least one cargo line (tentative + live combined).',
        ],
        'tentative' => [
            'label' => 'Tentative Bookings',
            'description' => 'Bookings with no transaction details yet.',
        ],
        'live' => [
            'label' => 'Live Bookings',
            'description' => 'Bookings with transaction details.',
        ],
        'for_atw' => [
            'label' => 'For ATW',
            'description' => 'Bookings for truck assignment.',
        ],
        'for_can' => [
            'label' => 'For CAN',
            'description' => 'Cargoes accepted for receiving (Rolling and Other Cargoes).',
        ],
        'for_cv_assignment' => [
            'label' => 'For CV Assignment',
            'description' => 'Bookings for convan assignment (if applicable).',
        ],
        'for_documentation' => [
            'label' => 'For Documentation',
            'description' => 'Bookings for EIR and Gatepass.',
        ],
        'for_gate_out' => [
            'label' => 'For Gate Out',
            'description' => 'Ready to scan out (ATW/CAN issued) but still inside the yard.',
        ],
        'partial_in_yard' => [
            'label' => 'Partial In Yard',
            'description' => 'Bookings with incomplete documentation but already inside the yard.',
        ],
        'pickup_in_transit' => [
            'label' => 'Pick-up In Transit',
            'description' => 'Scanned out, not yet scanned back in.',
        ],
        'in_yard' => [
            'label' => 'In Yard',
            'description' => 'With EIR In and not a foul trip, waiting for vessel loading.',
        ],
        'for_vessel_loading' => [
            'label' => 'For Vessel Loading',
            'description' => 'In Yard bookings with an assigned vessel voyage.',
        ],
        'shut_out' => [
            'label' => 'Shut Out',
            'description' => 'In Yard or For Vessel Loading but bumped to the next vessel voyage.',
        ],
    ];

    public function index()
    {
        $tentativeCount = Booking::query()->tentative()->count();
        $liveCount = Booking::query()->live()->count();

        $counts = [
            'cargo_build_up' => $tentativeCount + $liveCount,
            'tentative' => $tentativeCount,
            'live' => $liveCount,
            'for_atw' => Booking::query()->forAtw()->count(),
            'for_can' => Booking::query()->forCan()->count(),
            'for_cv_assignment' => Booking::query()->forCvAssignment()->count(),
            'for_documentation' => Booking::query()->forDocumentation()->count(),
            'for_gate_out' => Booking::query()->forGateOut()->count(),
            'pickup_in_transit' => Booking::query()->pickupInTransit()->count(),
            'partial_in_yard' => Booking::query()->partialInYard()->count(),
            'in_yard' => Booking::query()->inYard()->count(),
            'for_vessel_loading' => Booking::query()->forVesselLoading()->count(),
            'shut_out' => Booking::query()->shutOut()->count(),
        ];

        $buckets = collect(self::BUCKETS)->map(fn ($meta, $key) => [
            'key' => $key,
            'label' => $meta['label'],
            'description' => $meta['description'],
            'tracked' => in_array($key, self::TRACKED_BUCKETS, true),
            'count' => $counts[$key] ?? null,
        ])->values();

        return response()->json(['success' => true, 'data' => $buckets]);
    }

    public function bookings(Request $request)
    {
        $bucket = $request->get('bucket', 'cargo_build_up');

        if (! in_array($bucket, self::TRACKED_BUCKETS, true)) {
            return response()->json([
                'success' => false,
                'message' => 'This bucket is not yet tracked - it depends on a feature not built yet.',
            ], 422);
        }

        $query = Booking::query()->with([
            'client', 'lines.originPort', 'lines.destinationPort', 'lines.deliveryType', 'lines.dispatchDocument',
            'containerUnits.eirOut', 'containerUnits.eirIn', 'containerUnits.vesselVoyage', 'containerUnits.relayPort',
        ]);

        match ($bucket) {
            'tentative' => $query->tentative(),
            'live' => $query->live(),
            'for_atw' => $query->forAtw(),
            'for_can' => $query->forCan(),
            'for_cv_assignment' => $query->forCvAssignment(),
            'for_documentation' => $query->forDocumentation(),
            'for_gate_out' => $query->forGateOut(),
            'pickup_in_transit' => $query->pickupInTransit(),
            'partial_in_yard' => $query->partialInYard(),
            'in_yard' => $query->inYard(),
            'for_vessel_loading' => $query->forVesselLoading(),
            'shut_out' => $query->shutOut(),
            default => $query->has('lines'), // cargo_build_up
        };

        $bookings = $query->latest('booking_id')->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $bookings]);
    }
}
