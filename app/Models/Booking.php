<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    use HasUuids;

    protected $primaryKey = 'booking_id';

    public const STATUS_DRAFT = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_IN_TRANSIT = 3;
    public const STATUS_DELIVERED = 4;
    public const STATUS_COMPLETED = 5;
    public const STATUS_CANCELLED = 6;

    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_IN_TRANSIT => 'In Transit',
        self::STATUS_DELIVERED => 'Delivered',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    protected $fillable = [
        'code', 'client_id', 'client_contract_id', 'status',
        'vat_rate_id', 'contract_id', 'contract_rate_id',
        'trucking_snapshot', 'vat_amount_snapshot', 'grand_total_snapshot',
        'booking_date', 'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'status' => 'integer',
        'trucking_snapshot' => 'decimal:2',
        'vat_amount_snapshot' => 'decimal:2',
        'grand_total_snapshot' => 'decimal:2',
        'booking_date' => 'date:M d, Y',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * BK-{year}-0001, resetting each year. Same lockForUpdate-based
     * collision pattern as ClientMaster::generateNextCustomerCode.
     */
    public static function generateNextCode(): string
    {
        return DB::transaction(function () {
            $prefix = 'BK-'.now()->format('Y').'-';

            $seq = DB::table('bookings')
                ->where('code', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->pluck('code')
                ->map(fn ($code) => (int) substr($code, strlen($prefix)))
                ->max() ?? 0;

            do {
                $seq++;
                $candidate = $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            } while (self::where('code', $candidate)->exists());

            return $candidate;
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }

    public function clientContract(): BelongsTo
    {
        return $this->belongsTo(ClientContract::class, 'client_contract_id');
    }

    public function vatRate(): BelongsTo
    {
        return $this->belongsTo(VatRate::class, 'vat_rate_id', 'vat_rate_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function portCharges(): HasMany
    {
        return $this->hasMany(BookingPortCharge::class, 'booking_id', 'booking_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(BookingLine::class, 'booking_id', 'booking_id');
    }

    public function containerUnits(): HasMany
    {
        return $this->hasMany(BookingContainerUnit::class, 'booking_id', 'booking_id');
    }

    public function dispatchDocuments(): HasMany
    {
        return $this->hasMany(BookingDispatchDocument::class, 'booking_id', 'booking_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class, 'booking_id', 'booking_id')->orderByDesc('changed_at');
    }

    public function invoice()
    {
        return $this->hasOne(BookingInvoice::class, 'booking_id', 'booking_id');
    }

    public function billOfLading()
    {
        return $this->hasOne(BillOfLading::class, 'booking_id', 'booking_id');
    }

    /**
     * SOP Step 2's "Live" bucket - every line has transaction details
     * filled in (consignee, cargo type, declared value, delivery date).
     * A booking with no lines yet, or with any line still missing one of
     * those, is "Tentative" instead.
     */
    public function hasTransactionDetails(): bool
    {
        if ($this->lines->isEmpty()) {
            return false;
        }

        return $this->lines->every(fn (BookingLine $line) => $line->hasTransactionDetails());
    }

    private static function missingTransactionDetails($query)
    {
        return $query->whereNull('consignee_name')
            ->orWhereNull('cargo_type')
            ->orWhereNull('declared_value')
            ->orWhereNull('delivery_date');
    }

    /** Bookings with at least one line still missing transaction details. */
    public function scopeTentative($query)
    {
        return $query->whereHas('lines', fn ($q) => self::missingTransactionDetails($q));
    }

    /** Bookings with lines, all of which have transaction details filled in. */
    public function scopeLive($query)
    {
        return $query->has('lines')->whereDoesntHave('lines', fn ($q) => self::missingTransactionDetails($q));
    }

    /**
     * SOP Step 3 buckets. A Live booking with at least one line that still
     * needs its ATW (or CAN) generated falls in the matching bucket; once
     * every line has its dispatch document, the booking naturally drops
     * out of both and becomes eligible for "For CV Assignment" instead.
     */
    public function scopeForAtw($query)
    {
        return $query->live()->whereHas('lines', function ($q) {
            $q->whereDoesntHave('dispatchDocument')
                ->where(function ($q2) {
                    $q2->whereHas('deliveryType', fn ($q3) => $q3->where('includes_origin_trucking', true))
                        ->orWhereHas('booking.client', fn ($q3) => $q3->where('always_route_atw', true));
                });
        });
    }

    public function scopeForCan($query)
    {
        return $query->live()->whereHas('lines', function ($q) {
            $q->whereDoesntHave('dispatchDocument')
                ->whereHas('deliveryType', fn ($q3) => $q3->where('includes_origin_trucking', false))
                ->whereDoesntHave('booking.client', fn ($q3) => $q3->where('always_route_atw', true));
        });
    }

    /** Every line already has its dispatch document, but a container unit still needs its CV paperwork filled in. */
    public function scopeForCvAssignment($query)
    {
        return $query->live()
            ->whereDoesntHave('lines', fn ($q) => $q->whereDoesntHave('dispatchDocument'))
            ->whereHas('containerUnits', fn ($q) => $q->whereNull('proforma_bl_number')->orWhereNull('waybill_number'));
    }

    /**
     * SOP Steps 5-7 (EIR Out + Gate Pass). "For Documentation" is a unit
     * whose dispatch document exists but EIR Out hasn't been issued yet.
     * "For Gate Out" is EIR Out issued but not yet physically scanned out.
     * "Pick-up In Transit" is scanned out but not back in yet.
     */
    public function scopeForDocumentation($query)
    {
        return $query->live()->whereHas('containerUnits', function ($q) {
            $q->whereHas('bookingLine.dispatchDocument')->whereDoesntHave('eirOut');
        });
    }

    public function scopeForGateOut($query)
    {
        return $query->live()->whereHas('containerUnits', function ($q) {
            $q->whereNull('actual_gate_out_at')
                ->whereHas('bookingLine.dispatchDocument')
                ->whereHas('eirOut');
        });
    }

    public function scopePickupInTransit($query)
    {
        return $query->live()->whereHas('containerUnits', function ($q) {
            $q->whereNotNull('actual_gate_out_at')->whereNull('actual_gate_in_at');
        });
    }

    /**
     * SOP Step 9 (EIR In). "Partial In Yard" is physically back but EIR In
     * paperwork hasn't caught up yet. "In Yard" is EIR In done and the
     * line's trip type (from its dispatch document) isn't a foul trip -
     * a null trip type doesn't count as foul, it just means one was never
     * recorded.
     */
    public function scopePartialInYard($query)
    {
        return $query->live()->whereHas('containerUnits', function ($q) {
            $q->whereNotNull('actual_gate_in_at')->whereDoesntHave('eirIn');
        });
    }

    private static function inYardCondition($q)
    {
        return $q->whereHas('eirIn')->whereHas('bookingLine.dispatchDocument', function ($q2) {
            $q2->whereNull('trip_type')->orWhereNotIn('trip_type', ['Tandem Foul', 'Single Foul']);
        });
    }

    public function scopeInYard($query)
    {
        return $query->live()->whereHas('containerUnits', fn ($q) => self::inYardCondition($q));
    }

    /**
     * SOP Steps 10-11 (Voyage Plan / Loadlist). "For Vessel Loading" is an
     * In Yard unit with a voyage assigned and not shut out. "Shut Out" is
     * any unit CSR tagged as having missed its vessel's actual cutoff -
     * from either In Yard or For Vessel Loading per the SOP, so this only
     * checks shut_out_at itself rather than re-deriving which stage it
     * was tagged from.
     */
    public function scopeForVesselLoading($query)
    {
        return $query->live()->whereHas('containerUnits', function ($q) {
            self::inYardCondition($q)->whereNotNull('vessel_voyage_id')->whereNull('shut_out_at');
        });
    }

    public function scopeShutOut($query)
    {
        return $query->live()->whereHas('containerUnits', fn ($q) => $q->whereNotNull('shut_out_at'));
    }
}
