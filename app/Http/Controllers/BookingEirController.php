<?php

namespace App\Http\Controllers;

use App\Models\BookingContainerEirRecord;
use App\Models\BookingContainerUnit;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

/**
 * SOP Steps 5 & 9: EIR Out (issued before a container can gate out) and
 * EIR In (issued after it physically returns). Admin/office paperwork,
 * not a pier-scan action - lives in the View Booking modal alongside the
 * Phase 3 dispatch-document section.
 */
class BookingEirController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /** Generic upload used for the checklist, damage photos, and the driver ID photo. */
    public function uploadFile(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $paths = $this->fileUploadService->uploadFile([$validated['file']], 'uploads/booking/eir');

        return response()->json(['success' => true, 'data' => ['path' => $paths[0] ?? null]]);
    }

    public function issueOut(Request $request, BookingContainerUnit $bookingContainerUnit)
    {
        if ($bookingContainerUnit->eirOut()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'EIR Out has already been issued for this container.',
            ], 422);
        }

        $line = $bookingContainerUnit->bookingLine;

        if (! $line?->dispatchDocument()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "This line needs its ATW/CAN dispatch document before EIR Out can be issued.",
            ], 422);
        }

        // SOP: driver/shipper-rep ID photo is required for Pier-origin,
        // non-Trigo lines (the same condition that routes a line to CAN).
        $driverIdRequired = $line->needsCan();

        $validated = $request->validate([
            'damage_codes' => ['nullable', 'string', 'max:255'],
            'damage_remarks' => ['nullable', 'string'],
            'convan_checklist_path' => ['nullable', 'string', 'max:255'],
            'damage_photo_paths' => ['nullable', 'array'],
            'damage_photo_paths.*' => ['string'],
            'shipper_representative_name' => ['nullable', 'string', 'max:255'],
            'driver_id_photo_path' => [$driverIdRequired ? 'required' : 'nullable', 'string', 'max:255'],
        ]);

        $record = BookingContainerEirRecord::create(array_merge($validated, [
            'booking_container_unit_id' => $bookingContainerUnit->id,
            'direction' => BookingContainerEirRecord::DIRECTION_OUT,
            'issued_by' => $request->user()?->id,
            'issued_at' => now(),
        ]));

        return response()->json(['success' => true, 'data' => $record], 201);
    }

    public function issueIn(Request $request, BookingContainerUnit $bookingContainerUnit)
    {
        if ($bookingContainerUnit->eirIn()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'EIR In has already been issued for this container.',
            ], 422);
        }

        if ($bookingContainerUnit->actual_gate_in_at === null) {
            return response()->json([
                'success' => false,
                'message' => 'This container has not been scanned back in at the gate yet.',
            ], 422);
        }

        $validated = $request->validate([
            'damage_codes' => ['nullable', 'string', 'max:255'],
            'damage_remarks' => ['nullable', 'string'],
            'convan_checklist_path' => ['nullable', 'string', 'max:255'],
            'damage_photo_paths' => ['nullable', 'array'],
            'damage_photo_paths.*' => ['string'],
            'convan_class_id' => ['nullable', 'integer', 'exists:container_class,id'],
        ]);

        $record = BookingContainerEirRecord::create(array_merge($validated, [
            'booking_container_unit_id' => $bookingContainerUnit->id,
            'direction' => BookingContainerEirRecord::DIRECTION_IN,
            'issued_by' => $request->user()?->id,
            'issued_at' => now(),
        ]));

        return response()->json(['success' => true, 'data' => $record], 201);
    }
}
