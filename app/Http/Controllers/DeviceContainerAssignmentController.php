<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingContainerUnit;
use App\Models\ContainerAsset;
use App\Services\ContainerReservationService;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Dormant integration point for an external QR-scanner device to assign a
 * physical container to a booking slot directly, without a browser
 * session - not called by anything yet. Token-authenticated via Sanctum
 * (see routes/api.php's `device/v1` group, gated by the `container.assign`
 * token ability rather than a user role) and kept entirely separate from
 * the session-authenticated ContainerAssignmentController so a leaked or
 * misconfigured device token can never reach anything a staff login can.
 *
 * Issue a token for a real device with `php artisan device:make-token`.
 * See MODULES.md for the full module writeup.
 */
class DeviceContainerAssignmentController extends Controller
{
    public function __construct(protected ContainerReservationService $reservationService) {}

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'gate_pass_code' => ['required', 'string'],
            'container_no' => ['required', 'string'],
        ]);

        $unit = BookingContainerUnit::where('gate_pass_code', $validated['gate_pass_code'])->first();

        if (! $unit) {
            return response()->json([
                'success' => false,
                'message' => 'No booking slot found for that gate pass code.',
            ], 404);
        }

        if ($unit->booking->status !== Booking::STATUS_DRAFT) {
            return response()->json([
                'success' => false,
                'message' => 'Containers can only be assigned while the booking is still Draft.',
            ], 422);
        }

        $asset = ContainerAsset::where('container_no', strtoupper(trim($validated['container_no'])))->first();

        if (! $asset) {
            return response()->json([
                'success' => false,
                'message' => 'No container found with that number.',
            ], 404);
        }

        try {
            $unit = $this->reservationService->assignToUnit($unit, $asset, $request->user()?->id);
        } catch (RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'booking_container_unit_id' => $unit->id,
                'container_no' => $unit->containerAsset->container_no,
                'booking_code' => $unit->booking->code,
                'unit_index' => $unit->unit_index,
            ],
        ]);
    }
}
