<?php

namespace App\Http\Controllers;

use App\Models\BookingContainerUnit;
use App\Models\BookingDispatchDocument;
use App\Models\BookingLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * SOP Steps 3-4: issuing a cargo line's ATW/CAN (the "For ATW"/"For CAN"
 * Cargo Build-Up buckets), then filling in each of its container units'
 * Proforma BL / Waybill / Seal numbers (the "For CV Assignment" bucket).
 */
class BookingDispatchController extends Controller
{
    public function generate(Request $request, BookingLine $bookingLine)
    {
        if ($bookingLine->dispatchDocument()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This line already has a dispatch document.',
            ], 422);
        }

        if (! $bookingLine->hasTransactionDetails()) {
            return response()->json([
                'success' => false,
                'message' => 'This line still needs its transaction details filled in before a dispatch document can be generated.',
            ], 422);
        }

        $validated = $request->validate([
            'is_single_pickup' => ['boolean'],
            'is_advance_pull_out' => ['boolean'],
            'trip_type' => ['nullable', 'in:'.implode(',', BookingDispatchDocument::TRIP_TYPES)],
            'trailer_capacity' => ['nullable', 'string', 'max:255'],
            'convan_count' => ['nullable', 'integer', 'min:0'],
            'convan_size' => ['nullable', 'string', 'max:255'],
            'authorized_trucker' => ['nullable', 'string', 'max:255'],
            'plate_number' => ['nullable', 'string', 'max:255'],
            'authorized_driver' => ['nullable', 'string', 'max:255'],
            'helper' => ['nullable', 'string', 'max:255'],
            'coordinator_checker' => ['nullable', 'string', 'max:255'],
            'cy_empty_pull_out_at' => ['nullable', 'date'],
            'cy_stuffing_activity_at' => ['nullable', 'date'],
            'cy_stripping_activity_at' => ['nullable', 'date'],
            'cy_delivery_of_cargo_at' => ['nullable', 'date'],
            'estimated_departure_at' => ['nullable', 'date'],
            'estimated_arrival_at' => ['nullable', 'date'],
        ]);

        // Auto-determined by the confirmed SOP rule - never accepted from
        // the client, so a caller can't misfile a CAN line as an ATW (or
        // vice versa) by mistake.
        $documentType = $bookingLine->dispatchDocumentType();

        $document = DB::transaction(fn () => BookingDispatchDocument::create(array_merge($validated, [
            'booking_line_id' => $bookingLine->id,
            'booking_id' => $bookingLine->booking_id,
            'document_type' => $documentType,
            'document_number' => BookingDispatchDocument::generateNextNumber($documentType),
            'generated_by' => $request->user()?->id,
            'generated_at' => now(),
        ])));

        return response()->json(['success' => true, 'data' => $document], 201);
    }

    public function updateCvAssignment(Request $request, BookingContainerUnit $bookingContainerUnit)
    {
        $validated = $request->validate([
            'proforma_bl_number' => ['nullable', 'string', 'max:255'],
            'waybill_number' => ['nullable', 'string', 'max:255'],
            'seal_no' => ['nullable', 'string', 'max:255'],
        ]);

        $bookingContainerUnit->update($validated);

        return response()->json(['success' => true, 'data' => $bookingContainerUnit]);
    }
}
