<?php

namespace App\Http\Controllers;

use App\Models\BookingContainerUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * SOP Steps 6-7: scan-confirmation for Gate Pass Out/In. EIR's damage
 * paperwork (codes, checklist, photos) is a separate, later phase - this
 * only records the physical movement event and stamps who/when.
 */
class BookingGateScanController extends Controller
{
    /**
     * Units currently awaiting a gate action - backs both the Pier
     * Check-In list and its "Print List" of QR codes.
     */
    public function pending()
    {
        $units = BookingContainerUnit::query()
            ->with([
                'containerAsset', 'booking.client',
                'bookingLine.dispatchDocument', 'bookingLine.originPort', 'bookingLine.destinationPort',
            ])
            ->whereHas('booking', fn ($q) => $q->live())
            ->where(function ($q) {
                $q->whereNull('actual_gate_out_at')
                    ->orWhere(fn ($q2) => $q2->whereNotNull('actual_gate_out_at')->whereNull('actual_gate_in_at'));
            })
            ->get()
            ->filter(fn (BookingContainerUnit $unit) => $unit->nextGateAction() !== null)
            ->values();

        return response()->json(['success' => true, 'data' => $units]);
    }

    /**
     * One scan (or typed code) resolves the container, auto-detects
     * whether this is its Gate Out or Gate In leg, and stamps it -
     * pier personnel never choose a direction themselves.
     */
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $unit = BookingContainerUnit::with('bookingLine.dispatchDocument')
            ->where('gate_pass_code', $validated['code'])
            ->first();

        if (! $unit) {
            return response()->json([
                'success' => false,
                'message' => 'No container matches that code.',
            ], 404);
        }

        $action = $unit->nextGateAction();

        if ($action === null) {
            $message = $unit->actual_gate_in_at
                ? 'This container has already completed its gate round trip.'
                : 'This container is not ready to gate out yet - its ATW/CAN dispatch document has not been issued.';

            return response()->json(['success' => false, 'message' => $message], 422);
        }

        DB::transaction(function () use ($unit, $action, $request) {
            if ($action === 'OUT') {
                $unit->update([
                    'gate_pass_out_number' => BookingContainerUnit::generateNextGatePassOutNumber(),
                    'actual_gate_out_at' => now(),
                    'gate_out_scanned_by' => $request->user()?->id,
                ]);
            } else {
                $unit->update([
                    'actual_gate_in_at' => now(),
                    'gate_in_scanned_by' => $request->user()?->id,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'action' => $action,
            'data' => $unit->fresh()->load('containerAsset', 'booking'),
        ]);
    }
}
