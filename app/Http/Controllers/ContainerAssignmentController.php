<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingContainerUnit;
use App\Models\BookingLine;
use App\Models\ContainerAsset;
use App\Services\ContainerReservationService;
use App\Services\TeamService;
use App\Support\RoleHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Cargo-yard step between Draft booking creation and Booking::confirm() -
 * claiming a specific ContainerAsset (its physical serial/container_no)
 * for each BookingContainerUnit slot a booking's lines call for.
 * Booking::confirm() already refuses to proceed while any slot is still
 * unassigned; this is the page that actually fills them in. Open to all
 * roles by design (no permission/nav.access gate), same as the Cargo
 * Build-Up board.
 */
class ContainerAssignmentController extends Controller
{
    public function __construct(protected ContainerReservationService $reservationService) {}

    protected function visibleUserIds(Request $request): ?array
    {
        return RoleHelper::hasAnyRole($request->user(), ['superadmin'])
            ? null
            : TeamService::accessibleUserIds($request->user())->all();
    }

    public function index(Request $request)
    {
        $visibleUserIds = $this->visibleUserIds($request);

        $query = Booking::query()
            ->where('status', Booking::STATUS_DRAFT)
            ->has('lines')
            ->visibleTo($visibleUserIds)
            ->with([
                'client',
                'lines.originPort.location',
                'lines.destinationPort.location',
                'lines.container',
                'lines.containerClass',
                'lines.containerSize',
                'lines.containerVariant',
                'lines.containerUnits.containerAsset',
            ]);

        match ($request->get('status', 'all')) {
            'needs_assignment' => $query->whereHas('containerUnits', fn ($q) => $q->whereNull('container_asset_id')),
            'fully_assigned' => $query->has('containerUnits')->whereDoesntHave('containerUnits', fn ($q) => $q->whereNull('container_asset_id')),
            default => null,
        };

        $bookings = $query->latest('booking_id')->paginate($request->get('per_page', 15));

        $draftBase = Booking::query()->where('status', Booking::STATUS_DRAFT)->has('lines')->visibleTo($visibleUserIds);

        $statusCounts = [
            'all' => (clone $draftBase)->count(),
            'needs_assignment' => (clone $draftBase)->whereHas('containerUnits', fn ($q) => $q->whereNull('container_asset_id'))->count(),
            'fully_assigned' => (clone $draftBase)->has('containerUnits')->whereDoesntHave('containerUnits', fn ($q) => $q->whereNull('container_asset_id'))->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $bookings,
            'status_counts' => $statusCounts,
        ]);
    }

    /**
     * Resolve a scanned/typed container_no to a container's info WITHOUT
     * assigning it yet - powers the scan modal's "confirm before you
     * commit" step. Read-only; also flags whether the resolved container
     * is even the right type/class/size for this slot, so the confirm
     * screen can warn before the user taps Confirm rather than only
     * finding out from the assign() call's 422.
     */
    public function lookup(Request $request, BookingContainerUnit $bookingContainerUnit)
    {
        $validated = $request->validate([
            'container_no' => ['required', 'string'],
        ]);

        $asset = ContainerAsset::with(['containerVariant.container', 'containerVariant.containerClass', 'containerVariant.containerSize'])
            ->where('container_no', strtoupper(trim($validated['container_no'])))
            ->first();

        if (! $asset) {
            return response()->json([
                'success' => false,
                'message' => 'No container found with that number.',
            ], 404);
        }

        $line = $bookingContainerUnit->bookingLine;
        $variant = $asset->containerVariant;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $asset->id,
                'container_no' => $asset->container_no,
                'status_label' => ContainerAsset::STATUS_LABELS[$asset->status] ?? (string) $asset->status,
                'is_available' => $asset->status === ContainerAsset::STATUS_AVAILABLE,
                'variant_label' => trim(
                    ($variant?->container?->name ?? 'Container').' / '.
                    ($variant?->containerClass?->class ?? 'No class').' / '.
                    ($variant?->containerSize?->size ?? 'No size')
                ),
                'type_matches' => $asset->container_variant_id === $line->container_variant_id,
            ],
        ]);
    }

    /**
     * Manual pick (or repick) of one slot - either by choosing a specific
     * container_asset_id (legacy dropdown, kept for compatibility) or by
     * container_no (the cargo yard's scan-or-type flow: a QR scan or a
     * manually typed container number both resolve to a container_no
     * here). Releases whatever this unit currently holds first so a
     * reassignment never leaks a stale "Booked" container back into
     * circulation.
     */
    public function assign(Request $request, BookingContainerUnit $bookingContainerUnit)
    {
        if ($bookingContainerUnit->booking->status !== Booking::STATUS_DRAFT) {
            return response()->json([
                'success' => false,
                'message' => 'Containers can only be assigned while the booking is still Draft.',
            ], 422);
        }

        $validated = $request->validate([
            'container_asset_id' => ['required_without:container_no', 'nullable', 'integer', 'exists:container_assets,id'],
            'container_no' => ['required_without:container_asset_id', 'nullable', 'string'],
        ]);

        $asset = isset($validated['container_asset_id'])
            ? ContainerAsset::find($validated['container_asset_id'])
            : ContainerAsset::where('container_no', strtoupper(trim($validated['container_no'])))->first();

        if (! $asset) {
            return response()->json([
                'success' => false,
                'message' => 'No container found with that number.',
            ], 422);
        }

        try {
            $unit = $this->reservationService->assignToUnit($bookingContainerUnit, $asset, $request->user()?->id);
        } catch (RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'data' => $unit]);
    }

    public function unassign(Request $request, BookingContainerUnit $bookingContainerUnit)
    {
        if ($bookingContainerUnit->booking->status !== Booking::STATUS_DRAFT) {
            return response()->json([
                'success' => false,
                'message' => 'Containers can only be unassigned while the booking is still Draft.',
            ], 422);
        }

        if (! $bookingContainerUnit->container_asset_id) {
            return response()->json(['success' => false, 'message' => 'This slot has no container assigned.'], 422);
        }

        DB::transaction(function () use ($bookingContainerUnit, $request) {
            $this->reservationService->release([$bookingContainerUnit->container_asset_id], $request->user()?->id);
            $bookingContainerUnit->update(['container_asset_id' => null]);
        });

        return response()->json(['success' => true, 'data' => $bookingContainerUnit->fresh()]);
    }

    /**
     * One click, whole booking: for every line still short of containers,
     * auto-picks its remaining slots (same-port-first, longest-idle
     * ranking - ContainerReservationService::reserveAuto). Lines that run
     * out of available stock are reported back rather than failing the
     * whole batch, so partial progress isn't lost.
     */
    public function autoAssignBooking(Request $request, Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_DRAFT) {
            return response()->json(['success' => false, 'message' => 'Only a Draft booking can have containers auto-assigned.'], 422);
        }

        $lines = BookingLine::where('booking_id', $booking->booking_id)
            ->whereHas('containerUnits', fn ($q) => $q->whereNull('container_asset_id'))
            ->get();

        $shortfalls = [];

        foreach ($lines as $line) {
            $remaining = $line->containerUnits()->whereNull('container_asset_id')->get();

            try {
                $assets = DB::transaction(fn () => $this->reservationService->reserveAuto(
                    $line->container_variant_id,
                    $line->origin_port_id,
                    $remaining->count(),
                    $request->user()?->id
                ));

                foreach ($remaining->values() as $index => $unit) {
                    $unit->update(['container_asset_id' => $assets[$index]->id]);
                }
            } catch (RuntimeException $e) {
                $shortfalls[] = [
                    'booking_line_id' => $line->id,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $booking->fresh()->load([
                'lines.originPort.location',
                'lines.destinationPort.location',
                'lines.container',
                'lines.containerClass',
                'lines.containerSize',
                'lines.containerVariant',
                'lines.containerUnits.containerAsset',
            ]),
            'shortfalls' => $shortfalls,
        ]);
    }
}
