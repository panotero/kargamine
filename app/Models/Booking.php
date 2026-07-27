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
        'lane_id', 'origin_area_id', 'destination_area_id', 'delivery_type_id',
        'tariff_rate_id', 'vat_rate_id', 'contract_id', 'contract_rate_id',
        'trucking_snapshot', 'vat_amount_snapshot', 'grand_total_snapshot',
        'booking_date', 'created_by',
    ];

    protected $casts = [
        'status' => 'integer',
        'trucking_snapshot' => 'decimal:2',
        'vat_amount_snapshot' => 'decimal:2',
        'grand_total_snapshot' => 'decimal:2',
        'booking_date' => 'date',
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

    public function lane(): BelongsTo
    {
        return $this->belongsTo(Lane::class, 'lane_id', 'lane_id');
    }

    public function originArea(): BelongsTo
    {
        return $this->belongsTo(ServiceableArea::class, 'origin_area_id', 'area_id');
    }

    public function destinationArea(): BelongsTo
    {
        return $this->belongsTo(ServiceableArea::class, 'destination_area_id', 'area_id');
    }

    public function deliveryType(): BelongsTo
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_type_id', 'delivery_type_id');
    }

    public function tariffRate(): BelongsTo
    {
        return $this->belongsTo(LaneTariffRate::class, 'tariff_rate_id', 'rate_id');
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
}
