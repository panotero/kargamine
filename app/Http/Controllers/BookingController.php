<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPortCharge;
use App\Services\RateResolutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct(protected RateResolutionService $rateResolver) {}

    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->with(['lane.originPort', 'lane.destinationPort', 'deliveryType', 'contract'])
            ->when($request->filled('lane_id'), fn($q) => $q->where('lane_id', $request->lane_id))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('booking_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('booking_date', '<=', $request->date_to))
            ->latest('booking_id')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    public function show(Booking $booking)
    {
        return response()->json([
            'success' => true,
            'data' => $booking->load([
                'lane.originPort',
                'lane.destinationPort',
                'originArea',
                'destinationArea',
                'deliveryType',
                'tariffRate',
                'vatRate',
                'contract',
                'contractRate',
                'portCharges.port',
                'portCharges.chargeType',
            ]),
        ]);
    }

    /**
     * Live rate preview for the booking form - runs the same resolver
     * used at booking time but persists nothing. Lets the UI show the
     * full breakdown (and whether a contract discount applied) before
     * the user confirms.
     */
    public function quote(Request $request)
    {
        $validated = $this->validatePayload($request);

        $breakdown = $this->rateResolver->resolve($validated);

        return response()->json([
            'success' => true,
            'data' => $breakdown,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $breakdown = $this->rateResolver->resolve($validated);

        $booking = DB::transaction(function () use ($validated, $breakdown, $request) {
            $booking = Booking::create([
                'lane_id' => $breakdown['lane_id'],
                'origin_area_id' => $validated['origin_area_id'],
                'destination_area_id' => $validated['destination_area_id'],
                'delivery_type_id' => $validated['delivery_type_id'],
                'tariff_rate_id' => $breakdown['tariff_rate_id'],
                'vat_rate_id' => $breakdown['vat_rate_id'],
                'contract_id' => $breakdown['contract_id'],
                'contract_rate_id' => $breakdown['contract_rate_id'],
                'frt_snapshot' => $breakdown['frt'],
                'bsc_snapshot' => $breakdown['bsc'],
                'ra_snapshot' => $breakdown['ra'],
                'gri_snapshot' => $breakdown['gri'],
                'discount_type_snapshot' => $breakdown['discount_type'],
                'discount_value_snapshot' => $breakdown['discount_value'],
                'frt_after_discount_snapshot' => $breakdown['frt_after_discount'],
                'art_snapshot' => $breakdown['art'],
                'trucking_snapshot' => $breakdown['trucking']['total'],
                'vat_amount_snapshot' => $breakdown['vat_amount'],
                'grand_total_snapshot' => $breakdown['grand_total'],
                'booking_date' => $validated['booking_date'] ?? now()->toDateString(),
                'created_by' => $request->user()?->id,
            ]);

            foreach ($breakdown['port_charges']['origin'] as $charge) {
                BookingPortCharge::create([
                    'booking_id' => $booking->booking_id,
                    'port_id' => $validated['origin_port_id'],
                    'charge_type_id' => $charge['charge_type_id'],
                    'role' => 'ORIGIN',
                    'amount_snapshot' => $charge['amount'],
                ]);
            }

            foreach ($breakdown['port_charges']['destination'] as $charge) {
                BookingPortCharge::create([
                    'booking_id' => $booking->booking_id,
                    'port_id' => $validated['destination_port_id'],
                    'charge_type_id' => $charge['charge_type_id'],
                    'role' => 'DESTINATION',
                    'amount_snapshot' => $charge['amount'],
                ]);
            }

            return $booking;
        });

        return response()->json([
            'success' => true,
            'data' => $booking->load('portCharges.chargeType'),
        ], 201);
    }

    protected function validatePayload(Request $request): array
    {
        return $request->validate([
            'origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['required', 'integer', 'exists:ports,port_id', 'different:origin_port_id'],
            'origin_area_id' => ['required', 'integer', 'exists:serviceable_areas,area_id'],
            'destination_area_id' => ['required', 'integer', 'exists:serviceable_areas,area_id'],
            'delivery_type_id' => ['required', 'integer', 'exists:delivery_types,delivery_type_id'],
            'container_class' => ['required', 'integer'],
            'container_type' => ['required', 'integer'],
            'container_size' => ['required', 'integer'],
            'origin_service_type' => ['required', 'integer'],
            'destination_service_type' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            // Pass the lead_id when the client booking under a signed
            // contract - the resolver will auto-discount if a match exists,
            // and silently fall back to the standard tariff if it doesn't.
            'lead_id' => ['nullable', 'integer'],
            'booking_date' => ['nullable', 'date'],
        ]);
    }
}
