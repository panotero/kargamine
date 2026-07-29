<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BookingDispatchDocument extends Model
{
    public const TYPE_ATW = 'ATW';

    public const TYPE_CAN = 'CAN';

    public const TRIP_TYPES = ['Tandem', 'Tandem Foul', 'Single', 'Single Foul'];

    protected $fillable = [
        'booking_line_id',
        'booking_id',
        'document_type',
        'document_number',
        'generated_by',
        'generated_at',
        'is_single_pickup',
        'is_advance_pull_out',
        'trip_type',
        'trailer_capacity',
        'convan_count',
        'convan_size',
        'authorized_trucker',
        'plate_number',
        'authorized_driver',
        'helper',
        'coordinator_checker',
        'cy_empty_pull_out_at',
        'cy_stuffing_activity_at',
        'cy_stripping_activity_at',
        'cy_delivery_of_cargo_at',
        'estimated_departure_at',
        'estimated_arrival_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'generated_at' => 'datetime:M d, Y, h:i A',
        'is_single_pickup' => 'boolean',
        'is_advance_pull_out' => 'boolean',
        'convan_count' => 'integer',
        'cy_empty_pull_out_at' => 'datetime:M d, Y, h:i A',
        'cy_stuffing_activity_at' => 'datetime:M d, Y, h:i A',
        'cy_stripping_activity_at' => 'datetime:M d, Y, h:i A',
        'cy_delivery_of_cargo_at' => 'datetime:M d, Y, h:i A',
        'estimated_departure_at' => 'datetime:M d, Y, h:i A',
        'estimated_arrival_at' => 'datetime:M d, Y, h:i A',
    ];

    public function bookingLine(): BelongsTo
    {
        return $this->belongsTo(BookingLine::class, 'booking_line_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * ATW-YYYYMM-0001 / CAN-YYYYMM-0001, resetting each month - same
     * lockForUpdate collision pattern as Booking::generateNextCode.
     */
    public static function generateNextNumber(string $documentType): string
    {
        return DB::transaction(function () use ($documentType) {
            $prefix = $documentType.'-'.now()->format('Ym').'-';

            $seq = DB::table('booking_dispatch_documents')
                ->where('document_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->pluck('document_number')
                ->map(fn ($number) => (int) substr($number, strlen($prefix)))
                ->max() ?? 0;

            do {
                $seq++;
                $candidate = $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            } while (self::where('document_number', $candidate)->exists());

            return $candidate;
        });
    }
}
