<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BookingInvoice extends Model
{
    public const STATUS_DRAFT = 1;
    public const STATUS_SENT = 2;
    public const STATUS_PAID = 3;
    public const STATUS_VOID = 4;

    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_SENT => 'Sent',
        self::STATUS_PAID => 'Paid',
        self::STATUS_VOID => 'Void',
    ];

    protected $fillable = [
        'booking_id',
        'client_id',
        'invoice_number',
        'status',
        'amount',
        'due_date',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'status' => 'integer',
        'amount' => 'decimal:2',
        'due_date' => 'date:M d, Y',
    ];

    /**
     * INV-{year}-0001, resetting each year. Same lockForUpdate-based
     * collision pattern as ClientMaster::generateNextCustomerCode.
     */
    public static function generateNextNumber(): string
    {
        return DB::transaction(function () {
            $prefix = 'INV-'.now()->format('Y').'-';

            $seq = DB::table('booking_invoices')
                ->where('invoice_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->pluck('invoice_number')
                ->map(fn ($number) => (int) substr($number, strlen($prefix)))
                ->max() ?? 0;

            do {
                $seq++;
                $candidate = $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            } while (self::where('invoice_number', $candidate)->exists());

            return $candidate;
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
