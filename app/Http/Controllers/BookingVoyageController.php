<?php

namespace App\Http\Controllers;

use App\Models\BookingContainerUnit;
use Illuminate\Http\Request;

/**
 * SOP Step 11 (Generation of Loadlist) - assigning a container unit to a
 * vessel voyage leg, and tagging one Shut Out when it misses its
 * vessel's actual cutoff.
 */
class BookingVoyageController extends Controller
{
    public function assign(Request $request, BookingContainerUnit $bookingContainerUnit)
    {
        if (! $bookingContainerUnit->isInYard()) {
            return response()->json([
                'success' => false,
                'message' => 'This container is not In Yard yet - EIR In must be issued (and the trip must not be foul) before a vessel voyage can be assigned.',
            ], 422);
        }

        $validated = $request->validate([
            'vessel_voyage_id' => ['required', 'integer', 'exists:vessel_voyages,id'],
            'equivalent_teu' => ['nullable', 'numeric', 'min:0'],
            'relay_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
        ]);

        // Reassigning (e.g. after a Shut Out) always clears the prior tag.
        $bookingContainerUnit->update(array_merge($validated, ['shut_out_at' => null]));

        return response()->json([
            'success' => true,
            'data' => $bookingContainerUnit->fresh()->load('vesselVoyage', 'relayPort'),
        ]);
    }

    public function shutOut(BookingContainerUnit $bookingContainerUnit)
    {
        if (! $bookingContainerUnit->isInYard()) {
            return response()->json([
                'success' => false,
                'message' => 'Only a container that has reached In Yard can be tagged Shut Out.',
            ], 422);
        }

        $bookingContainerUnit->update(['shut_out_at' => now()]);

        return response()->json(['success' => true, 'data' => $bookingContainerUnit->fresh()]);
    }
}
