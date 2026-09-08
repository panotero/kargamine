<?php

namespace App\Http\Controllers;

use App\Models\BillOfLading;
use App\Models\Booking;
use App\Models\BookingContainerUnit;
use App\Models\BookingInvoice;
use App\Models\BookingLine;
use App\Models\BookingPortCharge;
use App\Models\BookingStatusHistory;
use App\Models\ClientContract;
use App\Models\ClientMaster;
use App\Models\ContainerVariant;
use App\Services\ContainerReservationService;
use App\Services\FileUploadService;
use App\Services\RateResolutionService;
use App\Services\TeamService;
use App\Support\RoleHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BookingController extends Controller
{
    public function __construct(
        protected RateResolutionService $rateResolver,
        protected ContainerReservationService $reservationService,
        protected FileUploadService $fileUploadService
    ) {}

    /** Supporting document for a cargo line flagged hazardous/dangerous goods. */
    public function uploadHazmatDocument(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $paths = $this->fileUploadService->uploadFile([$validated['file']], 'uploads/booking/hazmat');

        return response()->json(['success' => true, 'data' => ['path' => $paths[0] ?? null]]);
    }

    protected function withDisplayRelations($query)
    {
        return $query->with([
            'client',
            'clientContract',
            'lines.originPort.location',
            'lines.destinationPort.location',
            'lines.deliveryType',
            'lines.container',
            'lines.containerClass',
            'lines.containerSize',
            'lines.containerVariant',
            'lines.containerUnits.containerAsset',
            'lines.containerUnits.eirOut',
            'lines.containerUnits.eirIn',
            'lines.dispatchDocument',
            'lines.portCharges.port',
            'lines.portCharges.chargeType',
            // Booking::portCharges() is the same rows via booking_id - kept
            // eager loaded flat too since the view modal sums the whole
            // booking's charges in one pass rather than per line.
            'portCharges.port',
            'portCharges.chargeType',
            'statusHistory.changedBy',
            'invoice',
            'billOfLading',
        ]);
    }

    public function index(Request $request)
    {
        // Team-scoped visibility, same rule as CRM leads/Proposals/Clients/Contracts:
        // a member only sees their own clients' bookings, a team leader sees their
        // subtree, superadmin sees everything.
        $visibleUserIds = RoleHelper::hasAnyRole($request->user(), ['superadmin'])
            ? null
            : TeamService::accessibleUserIds($request->user())->all();

        $bookings = Booking::query()
            ->with(['client', 'lines.originPort.location', 'lines.destinationPort.location', 'lines.deliveryType'])
            ->visibleTo($visibleUserIds)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('client_id'), fn($q) => $q->where('client_id', $request->client_id))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('booking_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('booking_date', '<=', $request->date_to))
            ->latest('booking_id')
            ->paginate($request->get('per_page', 15));

        // Grouped COUNT, not Booking::all() - the earlier code review flagged
        // the load-everything-then-count-in-PHP pattern used elsewhere.
        $statusCounts = Booking::query()->visibleTo($visibleUserIds)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return response()->json([
            'success' => true,
            'status_counts' => [
                'all' => $statusCounts->sum(),
                'draft' => $statusCounts->get(Booking::STATUS_DRAFT, 0),
                'confirmed' => $statusCounts->get(Booking::STATUS_CONFIRMED, 0),
                'in_transit' => $statusCounts->get(Booking::STATUS_IN_TRANSIT, 0),
                'delivered' => $statusCounts->get(Booking::STATUS_DELIVERED, 0),
                'completed' => $statusCounts->get(Booking::STATUS_COMPLETED, 0),
                'cancelled' => $statusCounts->get(Booking::STATUS_CANCELLED, 0),
            ],
            'data' => $bookings,
        ]);
    }

    public function show(Booking $booking)
    {
        return response()->json([
            'success' => true,
            'data' => $this->withDisplayRelations(Booking::query())->findOrFail($booking->booking_id),
        ]);
    }

    /**
     * Live rate preview for the booking form - runs the same resolver
     * used at booking time but persists nothing.
     */
    public function quote(Request $request)
    {
        $validated = $this->validatePayload($request);
        $client = ClientMaster::findOrFail($validated['client_id']);
        $activeContract = $this->activeContractFor($client);

        [$header, $lines] = $this->buildResolverInput($validated, $activeContract);

        try {
            $breakdown = $this->rateResolver->resolve($header, $lines);
        } catch (RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'data' => $breakdown]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);
        $client = ClientMaster::findOrFail($validated['client_id']);
        $activeContract = $this->activeContractFor($client);

        [$header, $lines] = $this->buildResolverInput($validated, $activeContract);

        try {
            $breakdown = $this->rateResolver->resolve($header, $lines);

            $booking = DB::transaction(function () use ($validated, $breakdown, $activeContract, $request) {
                $booking = Booking::create([
                    'code' => Booking::generateNextCode(),
                    'client_id' => $validated['client_id'],
                    'client_contract_id' => $activeContract?->id,
                    'status' => Booking::STATUS_DRAFT,
                    'vat_rate_id' => $breakdown['vat_rate_id'],
                    'trucking_snapshot' => $breakdown['trucking']['total'],
                    'vat_amount_snapshot' => $breakdown['vat_amount'],
                    'grand_total_snapshot' => $breakdown['grand_total'],
                    'booking_date' => $validated['booking_date'] ?? now()->toDateString(),
                    'created_by' => $request->user()?->id,
                ]);

                $this->rebuildLinesUnitsAndCharges($booking, $breakdown, $validated, $request);

                BookingStatusHistory::create([
                    'booking_id' => $booking->booking_id,
                    'from_status' => null,
                    'to_status' => Booking::STATUS_DRAFT,
                    'changed_by' => $request->user()?->id,
                    'changed_at' => now(),
                ]);

                return $booking;
            });
        } catch (RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $this->withDisplayRelations(Booking::query())->findOrFail($booking->booking_id),
        ], 201);
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_DRAFT) {
            return response()->json(['success' => false, 'message' => 'Only Draft bookings can be edited.'], 422);
        }

        $validated = $this->validatePayload($request);
        $client = ClientMaster::findOrFail($validated['client_id']);
        $activeContract = $this->activeContractFor($client);

        [$header, $lines] = $this->buildResolverInput($validated, $activeContract);

        try {
            $breakdown = $this->rateResolver->resolve($header, $lines);

            DB::transaction(function () use ($booking, $validated, $breakdown, $activeContract, $request) {
                // Release whatever this booking currently has reserved before
                // rebuilding - lines/quantities/variants may have changed.
                $existingAssetIds = BookingContainerUnit::where('booking_id', $booking->booking_id)
                    ->whereNotNull('container_asset_id')
                    ->pluck('container_asset_id')
                    ->all();

                if ($existingAssetIds) {
                    $this->reservationService->release($existingAssetIds, $request->user()?->id);
                }

                BookingContainerUnit::where('booking_id', $booking->booking_id)->delete();
                BookingPortCharge::where('booking_id', $booking->booking_id)->delete();
                BookingLine::where('booking_id', $booking->booking_id)->delete();

                $booking->update([
                    'client_id' => $validated['client_id'],
                    'client_contract_id' => $activeContract?->id,
                    'vat_rate_id' => $breakdown['vat_rate_id'],
                    'trucking_snapshot' => $breakdown['trucking']['total'],
                    'vat_amount_snapshot' => $breakdown['vat_amount'],
                    'grand_total_snapshot' => $breakdown['grand_total'],
                    'booking_date' => $validated['booking_date'] ?? $booking->booking_date,
                ]);

                $this->rebuildLinesUnitsAndCharges($booking, $breakdown, $validated, $request);
            });
        } catch (RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $this->withDisplayRelations(Booking::query())->findOrFail($booking->booking_id),
        ]);
    }

    /**
     * Cargo/transaction detail fields (consignee, cargo type, declared
     * value, delivery dates, etc.) don't feed pricing or container
     * reservation, so - unlike update() above - this is allowed on a
     * booking in any status except Cancelled. Lets a line move Tentative
     * -> Live (Cargo Build-Up board) even after the booking's been
     * Confirmed, instead of being locked out with the rest of the form.
     */
    public function updateLineDetails(Request $request, Booking $booking, BookingLine $line)
    {
        if ($line->booking_id !== $booking->booking_id) {
            abort(404);
        }

        if ($booking->status === Booking::STATUS_CANCELLED) {
            return response()->json(['success' => false, 'message' => 'Cancelled bookings cannot be edited.'], 422);
        }

        $validated = $request->validate([
            'description' => ['nullable', 'string', 'max:255'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'volume_cbm' => ['nullable', 'numeric', 'min:0'],
            'consignee_name' => ['nullable', 'string', 'max:255'],
            'consignee_address' => ['nullable', 'string', 'max:255'],
            'consignee_contact_person' => ['nullable', 'string', 'max:255'],
            'consignee_contact_number' => ['nullable', 'string', 'max:50'],
            'cargo_type' => ['nullable', 'string', 'max:255'],
            'other_cargo_details' => ['nullable', 'string'],
            'declared_value' => ['nullable', 'numeric', 'min:0'],
            'delivery_date' => ['nullable', 'date'],
            'delivery_date_notes' => ['nullable', 'string', 'max:255'],
            'first_delivery_date' => ['nullable', 'date'],
            'last_delivery_date' => ['nullable', 'date', 'after_or_equal:first_delivery_date'],
        ]);

        $line->update($validated);

        return response()->json([
            'success' => true,
            'data' => $this->withDisplayRelations(Booking::query())->findOrFail($booking->booking_id),
        ]);
    }

    /**
     * Locks final pricing (re-resolved, in case rates moved since Draft) and
     * transitions Draft -> Confirmed. Container units stay unassigned at
     * this point on purpose - a specific ContainerAsset only gets attached
     * later at the cargo yard (see rebuildLinesUnitsAndCharges below).
     */
    public function confirm(Request $request, Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_DRAFT) {
            return response()->json(['success' => false, 'message' => 'Only Draft bookings can be confirmed.'], 422);
        }

        $lines = BookingLine::with(['deliveryType', 'originPort', 'destinationPort'])->where('booking_id', $booking->booking_id)->get();

        $requiredFields = [
            'consignee_name' => 'Consignee Name',
            'consignee_address' => 'Consignee Address',
            'consignee_contact_person' => 'Consignee Contact Person',
            'consignee_contact_number' => 'Consignee Contact Number',
            'delivery_date' => 'Delivery Date',
            'delivery_date_notes' => 'Delivery Date Notes',
            'first_delivery_date' => 'First Delivery Date',
            'last_delivery_date' => 'Last Delivery Date',
        ];

        $incompleteMessages = [];
        foreach ($lines as $index => $line) {
            $missing = [];
            foreach ($requiredFields as $field => $label) {
                if (blank($line->{$field})) {
                    $missing[] = $label;
                }
            }
            if ($missing) {
                $lineNumber = $index + 1;
                $incompleteMessages[] = "Line {$lineNumber} ({$line->originPort?->name} → {$line->destinationPort?->name}) is missing: " . implode(', ', $missing) . '.';
            }
        }

        if ($incompleteMessages) {
            return response()->json([
                'success' => false,
                'message' => "This booking can't be confirmed yet — delivery details are missing.\n" . implode("\n", $incompleteMessages),
            ], 422);
        }

        [$header, $resolverLines] = $this->buildResolverInputFromBooking($booking, $lines);

        try {
            $breakdown = $this->rateResolver->resolve($header, $resolverLines);
        } catch (RuntimeException $e) {
            return response()->json(['success' => false, 'message' => 'Unable to lock final pricing: ' . $e->getMessage()], 422);
        }

        DB::transaction(function () use ($booking, $breakdown, $lines, $request) {
            foreach ($lines as $index => $line) {
                $lineBreakdown = $breakdown['lines'][$index];

                $line->update([
                    'lane_id' => $lineBreakdown['lane_id'],
                    'tariff_rate_id' => $lineBreakdown['tariff_rate_id'],
                    'delivery_type_id' => $lineBreakdown['delivery_type_id'],
                    'frt_snapshot' => $lineBreakdown['frt'],
                    'discount_type_snapshot' => $lineBreakdown['discount_type'],
                    'discount_value_snapshot' => $lineBreakdown['discount_value'],
                    'frt_after_discount_snapshot' => $lineBreakdown['frt_after_discount'],
                    'line_total' => $lineBreakdown['line_total'],
                    'trucking_snapshot' => $lineBreakdown['trucking']['total'],
                ]);
            }

            $booking->update([
                'vat_rate_id' => $breakdown['vat_rate_id'],
                'trucking_snapshot' => $breakdown['trucking']['total'],
                'vat_amount_snapshot' => $breakdown['vat_amount'],
                'grand_total_snapshot' => $breakdown['grand_total'],
                'status' => Booking::STATUS_CONFIRMED,
            ]);

            BookingStatusHistory::create([
                'booking_id' => $booking->booking_id,
                'from_status' => Booking::STATUS_DRAFT,
                'to_status' => Booking::STATUS_CONFIRMED,
                'changed_by' => $request->user()?->id,
                'changed_at' => now(),
            ]);

            BookingInvoice::create([
                'booking_id' => $booking->booking_id,
                'client_id' => $booking->client_id,
                'invoice_number' => BookingInvoice::generateNextNumber(),
                'status' => BookingInvoice::STATUS_DRAFT,
                'amount' => $breakdown['grand_total'],
                // Fixed 30 days from booking date for now - editable directly
                // on the record later; no dedicated update endpoint in this pass.
                'due_date' => $booking->booking_date?->copy()->addDays(30) ?? now()->addDays(30),
            ]);

            BillOfLading::create([
                'booking_id' => $booking->booking_id,
                'bol_number' => BillOfLading::generateNextNumber(),
                'issued_at' => now(),
                'issued_by' => $request->user()?->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'data' => $this->withDisplayRelations(Booking::query())->findOrFail($booking->booking_id),
        ]);
    }

    public function downloadBol(Booking $booking)
    {
        $booking->load([
            'client',
            'lines.originPort.location',
            'lines.destinationPort.location',
            'lines.container',
            'lines.containerClass',
            'lines.containerSize',
            'lines.containerUnits.containerAsset',
            'billOfLading',
        ]);

        if (! $booking->billOfLading) {
            return response()->json(['success' => false, 'message' => 'This booking has no Bill of Lading yet - it must be Confirmed first.'], 422);
        }

        $pdf = Pdf::loadView('pdf.billOfLading', [
            'booking' => $booking,
            'bol' => $booking->billOfLading,
        ]);

        return $pdf->download($booking->billOfLading->bol_number . '.pdf');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if (! in_array($booking->status, [Booking::STATUS_DRAFT, Booking::STATUS_CONFIRMED], true)) {
            return response()->json(['success' => false, 'message' => 'Only Draft or Confirmed bookings can be cancelled.'], 422);
        }

        DB::transaction(function () use ($booking, $validated, $request) {
            $assetIds = BookingContainerUnit::where('booking_id', $booking->booking_id)
                ->whereNotNull('container_asset_id')
                ->pluck('container_asset_id')
                ->all();

            if ($assetIds) {
                $this->reservationService->release($assetIds, $request->user()?->id);
            }

            $fromStatus = $booking->status;
            $booking->update(['status' => Booking::STATUS_CANCELLED]);

            BookingStatusHistory::create([
                'booking_id' => $booking->booking_id,
                'from_status' => $fromStatus,
                'to_status' => Booking::STATUS_CANCELLED,
                'changed_by' => $request->user()?->id,
                'note' => $validated['reason'],
                'changed_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'data' => $this->withDisplayRelations(Booking::query())->findOrFail($booking->booking_id),
        ]);
    }

    public function markInTransit(Request $request, Booking $booking)
    {
        return $this->transitionStatus($request, $booking, Booking::STATUS_CONFIRMED, Booking::STATUS_IN_TRANSIT);
    }

    public function markDelivered(Request $request, Booking $booking)
    {
        return $this->transitionStatus($request, $booking, Booking::STATUS_IN_TRANSIT, Booking::STATUS_DELIVERED);
    }

    public function markCompleted(Request $request, Booking $booking)
    {
        return $this->transitionStatus($request, $booking, Booking::STATUS_DELIVERED, Booking::STATUS_COMPLETED);
    }

    /**
     * Strict prior-status transition, enforced server-side regardless of
     * what the frontend sends. These are supervisor overrides once the
     * Pier & Port Handling plan's auto-cascade exists; until then they're
     * the primary path.
     */
    protected function transitionStatus(Request $request, Booking $booking, int $expectedFrom, int $to)
    {
        if ($booking->status !== $expectedFrom) {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be ' . Booking::STATUS_LABELS[$expectedFrom] . ' to do this; it is currently ' . Booking::STATUS_LABELS[$booking->status] . '.',
            ], 422);
        }

        DB::transaction(function () use ($booking, $to, $request) {
            $from = $booking->status;
            $booking->update(['status' => $to]);

            BookingStatusHistory::create([
                'booking_id' => $booking->booking_id,
                'from_status' => $from,
                'to_status' => $to,
                'changed_by' => $request->user()?->id,
                'changed_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'data' => $this->withDisplayRelations(Booking::query())->findOrFail($booking->booking_id),
        ]);
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    protected function activeContractFor(ClientMaster $client): ?ClientContract
    {
        return ClientContract::where('client_id', $client->id)
            ->where('status', ClientContract::STATUS_ACTIVE)
            ->first();
    }

    protected function validatePayload(Request $request): array
    {
        $validated = $request->validate([
            'client_id' => ['required', 'integer', 'exists:client_masters,id'],
            'booking_date' => ['nullable', 'date'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.origin_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'lines.*.destination_port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'lines.*.origin_area_id' => ['required', 'integer', 'exists:serviceable_areas,area_id'],
            'lines.*.destination_area_id' => ['required', 'integer', 'exists:serviceable_areas,area_id'],
            'lines.*.origin_mode' => ['required', 'in:door,pier'],
            'lines.*.destination_mode' => ['required', 'in:door,pier'],
            // Only meaningful (and only shown on the form) when the matching
            // side's mode is Pier - origin only ever offers stuffing/van_out,
            // destination only ever offers stripping/van_out.
            'lines.*.origin_pier_handling' => ['nullable', 'in:stuffing,van_out'],
            'lines.*.destination_pier_handling' => ['nullable', 'in:stripping,van_out'],
            'lines.*.container_variant_id' => ['required', 'integer', 'exists:container_variants,id'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.description' => ['nullable', 'string', 'max:255'],
            'lines.*.weight_kg' => ['nullable', 'numeric', 'min:0'],
            'lines.*.volume_cbm' => ['nullable', 'numeric', 'min:0'],
            'lines.*.is_hazardous' => ['boolean'],
            'lines.*.is_fragile' => ['boolean'],
            'lines.*.hazardous_document_path' => ['nullable', 'string', 'max:2048'],
            'lines.*.minimum_temperature' => ['nullable', 'numeric'],

            // Route & Delivery "transaction details" are optional at Draft
            // save time - a booking can be saved with just a client and
            // route filled in. They become required before Confirm (see
            // confirm()'s completeness guard below). Container-side fields
            // (cargo_type, declared_value, description, weight/volume,
            // etc.) stay nullable/untouched.
            'lines.*.consignee_name' => ['nullable', 'string', 'max:255'],
            'lines.*.consignee_address' => ['nullable', 'string', 'max:255'],
            'lines.*.consignee_contact_person' => ['nullable', 'string', 'max:255'],
            'lines.*.consignee_contact_number' => ['nullable', 'string', 'max:50'],
            'lines.*.cargo_type' => ['nullable', 'string', 'max:255'],
            'lines.*.other_cargo_details' => ['nullable', 'string'],
            'lines.*.declared_value' => ['nullable', 'numeric', 'min:0'],
            'lines.*.delivery_date' => ['nullable', 'date'],
            'lines.*.delivery_date_notes' => ['nullable', 'string', 'max:255'],
            'lines.*.first_delivery_date' => ['nullable', 'date'],
            'lines.*.last_delivery_date' => ['nullable', 'date', 'after_or_equal:lines.*.first_delivery_date'],
        ]);

        foreach ($validated['lines'] as $index => $line) {
            if ($line['origin_port_id'] === $line['destination_port_id']) {
                $lineNumber = $index + 1;

                abort(response()->json([
                    'success' => false,
                    'message' => "Line {$lineNumber}: origin and destination port must be different.",
                ], 422));
            }
        }

        return $validated;
    }

    /**
     * @return array{0: array, 1: array}
     */
    protected function buildResolverInput(array $validated, ?ClientContract $activeContract): array
    {
        $header = [
            'booking_date' => $validated['booking_date'] ?? null,
            'client_contract_id' => $activeContract?->id,
        ];

        $lines = array_map(function ($line) {
            $variant = ContainerVariant::findOrFail($line['container_variant_id']);

            return [
                'container_id' => $variant->container_id,
                'container_class_id' => $variant->container_class_id,
                'container_size_id' => $variant->container_size_id,
                'container_variant_id' => $variant->id,
                'quantity' => $line['quantity'],
                'origin_port_id' => $line['origin_port_id'],
                'destination_port_id' => $line['destination_port_id'],
                'origin_area_id' => $line['origin_area_id'],
                'destination_area_id' => $line['destination_area_id'],
                'origin_mode' => $line['origin_mode'],
                'destination_mode' => $line['destination_mode'],
            ];
        }, $validated['lines']);

        return [$header, $lines];
    }

    /**
     * Same shape as buildResolverInput(), but reconstructed from what's
     * already stored on the booking's lines - used by confirm(), which
     * doesn't receive a fresh request payload. $lines must have
     * deliveryType eager loaded.
     */
    protected function buildResolverInputFromBooking(Booking $booking, $lines): array
    {
        $header = [
            'booking_date' => $booking->booking_date?->toDateString(),
            'client_contract_id' => $booking->client_contract_id,
        ];

        $resolverLines = $lines->map(fn($line) => [
            'container_id' => $line->container_id,
            'container_class_id' => $line->container_class_id,
            'container_size_id' => $line->container_size_id,
            'container_variant_id' => $line->container_variant_id,
            'quantity' => $line->quantity,
            'origin_port_id' => $line->origin_port_id,
            'destination_port_id' => $line->destination_port_id,
            'origin_area_id' => $line->origin_area_id,
            'destination_area_id' => $line->destination_area_id,
            'origin_mode' => $line->deliveryType->includes_origin_trucking ? 'door' : 'pier',
            'destination_mode' => $line->deliveryType->includes_destination_trucking ? 'door' : 'pier',
        ])->all();

        return [$header, $resolverLines];
    }

    /**
     * Creates booking_lines (with their own route/delivery/lane/tariff) +
     * booking_container_units (reserving containers per line, auto or
     * manual) + booking_port_charges (scoped to that line) for a booking
     * whose header row already exists. Used by both store() and update()
     * (update() wipes the old rows first, then calls this fresh).
     */
    protected function rebuildLinesUnitsAndCharges(Booking $booking, array $breakdown, array $validated, Request $request): void
    {
        $globalUnitSeq = 0;

        foreach ($breakdown['lines'] as $index => $lineBreakdown) {
            $inputLine = $validated['lines'][$index];

            $bookingLine = BookingLine::create([
                'booking_id' => $booking->booking_id,
                'origin_port_id' => $lineBreakdown['origin_port_id'],
                'destination_port_id' => $lineBreakdown['destination_port_id'],
                'origin_area_id' => $lineBreakdown['origin_area_id'],
                'destination_area_id' => $lineBreakdown['destination_area_id'],
                'delivery_type_id' => $lineBreakdown['delivery_type_id'],
                'origin_pier_handling' => $inputLine['origin_pier_handling'] ?? null,
                'destination_pier_handling' => $inputLine['destination_pier_handling'] ?? null,
                'lane_id' => $lineBreakdown['lane_id'],
                'tariff_rate_id' => $lineBreakdown['tariff_rate_id'],
                'container_id' => $lineBreakdown['container_id'],
                'container_class_id' => $lineBreakdown['container_class_id'],
                'container_size_id' => $lineBreakdown['container_size_id'],
                'container_variant_id' => $lineBreakdown['container_variant_id'],
                'quantity' => $lineBreakdown['quantity'],
                'description' => $inputLine['description'] ?? null,
                'weight_kg' => $inputLine['weight_kg'] ?? null,
                'volume_cbm' => $inputLine['volume_cbm'] ?? null,
                'is_hazardous' => $inputLine['is_hazardous'] ?? false,
                'is_fragile' => $inputLine['is_fragile'] ?? false,
                'hazardous_document_path' => $inputLine['hazardous_document_path'] ?? null,
                'minimum_temperature' => $inputLine['minimum_temperature'] ?? null,
                'consignee_name' => $inputLine['consignee_name'] ?? null,
                'consignee_address' => $inputLine['consignee_address'] ?? null,
                'consignee_contact_person' => $inputLine['consignee_contact_person'] ?? null,
                'consignee_contact_number' => $inputLine['consignee_contact_number'] ?? null,
                'cargo_type' => $inputLine['cargo_type'] ?? null,
                'other_cargo_details' => $inputLine['other_cargo_details'] ?? null,
                'declared_value' => $inputLine['declared_value'] ?? null,
                'delivery_date' => $inputLine['delivery_date'] ?? null,
                'delivery_date_notes' => $inputLine['delivery_date_notes'] ?? null,
                'first_delivery_date' => $inputLine['first_delivery_date'] ?? null,
                'last_delivery_date' => $inputLine['last_delivery_date'] ?? null,
                'frt_snapshot' => $lineBreakdown['frt'],
                'discount_type_snapshot' => $lineBreakdown['discount_type'],
                'discount_value_snapshot' => $lineBreakdown['discount_value'],
                'frt_after_discount_snapshot' => $lineBreakdown['frt_after_discount'],
                'line_total' => $lineBreakdown['line_total'],
                'trucking_snapshot' => $lineBreakdown['trucking']['total'],
            ]);

            // Containers are no longer reserved here - a specific
            // ContainerAsset gets assigned later at the cargo yard, which is
            // what actually flips that asset's status to booked. Each unit
            // starts unassigned (container_asset_id null) so cargo yard
            // staff have exactly `quantity` slots to fill in per line.
            for ($unitOffset = 0; $unitOffset < $bookingLine->quantity; $unitOffset++) {
                $globalUnitSeq++;

                BookingContainerUnit::create([
                    'booking_line_id' => $bookingLine->id,
                    'booking_id' => $booking->booking_id,
                    'unit_index' => $unitOffset + 1,
                    'gate_pass_code' => sprintf('GP-%s-%02d', $booking->code, $globalUnitSeq),
                    'container_asset_id' => null,
                    'status' => BookingContainerUnit::STATUS_PENDING,
                    'origin_port_id' => $lineBreakdown['origin_port_id'],
                    'destination_port_id' => $lineBreakdown['destination_port_id'],
                ]);
            }

            foreach ($lineBreakdown['port_charges']['origin'] as $charge) {
                BookingPortCharge::create([
                    'booking_id' => $booking->booking_id,
                    'booking_line_id' => $bookingLine->id,
                    'port_id' => $lineBreakdown['origin_port_id'],
                    'charge_type_id' => $charge['charge_type_id'],
                    'role' => 'ORIGIN',
                    'amount_snapshot' => $charge['amount'],
                ]);
            }

            foreach ($lineBreakdown['port_charges']['destination'] as $charge) {
                BookingPortCharge::create([
                    'booking_id' => $booking->booking_id,
                    'booking_line_id' => $bookingLine->id,
                    'port_id' => $lineBreakdown['destination_port_id'],
                    'charge_type_id' => $charge['charge_type_id'],
                    'role' => 'DESTINATION',
                    'amount_snapshot' => $charge['amount'],
                ]);
            }
        }
    }
}
