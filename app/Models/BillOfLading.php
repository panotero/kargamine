<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BillOfLading extends Model
{
    protected $fillable = [
        'booking_id',
        'bol_number',
        'issued_at',
        'issued_by',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'issued_at' => 'datetime:M d, Y, h:i A',
    ];

    /**
     * BOL-{year}-0001, resetting each year. Same lockForUpdate-based
     * collision pattern as ClientMaster::generateNextCustomerCode.
     */
    public static function generateNextNumber(): string
    {
        return DB::transaction(function () {
            $prefix = 'BOL-'.now()->format('Y').'-';

            $seq = DB::table('bill_of_ladings')
                ->where('bol_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->pluck('bol_number')
                ->map(fn ($number) => (int) substr($number, strlen($prefix)))
                ->max() ?? 0;

            do {
                $seq++;
                $candidate = $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            } while (self::where('bol_number', $candidate)->exists());

            return $candidate;
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
