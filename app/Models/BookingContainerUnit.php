<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class BookingContainerUnit extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_LOADED = 2;
    public const STATUS_DISCHARGED = 3;
    public const STATUS_EXCEPTION = 4;

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_LOADED => 'Loaded',
        self::STATUS_DISCHARGED => 'Discharged',
        self::STATUS_EXCEPTION => 'Exception',
    ];

    protected $fillable = [
        'booking_line_id',
        'booking_id',
        'unit_index',
        'gate_pass_code',
        'container_asset_id',
        'seal_no',
        'proforma_bl_number',
        'waybill_number',
        'status',
        'origin_port_id',
        'destination_port_id',
        'gate_pass_out_number',
        'actual_gate_out_at',
        'gate_out_scanned_by',
        'actual_gate_in_at',
        'gate_in_scanned_by',
        'vessel_voyage_id',
        'equivalent_teu',
        'relay_port_id',
        'shut_out_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'unit_index' => 'integer',
        'status' => 'integer',
        'actual_gate_out_at' => 'datetime:M d, Y, h:i A',
        'actual_gate_in_at' => 'datetime:M d, Y, h:i A',
        'equivalent_teu' => 'decimal:2',
        'shut_out_at' => 'datetime:M d, Y, h:i A',
    ];

    public function bookingLine(): BelongsTo
    {
        return $this->belongsTo(BookingLine::class, 'booking_line_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function containerAsset(): BelongsTo
    {
        return $this->belongsTo(ContainerAsset::class, 'container_asset_id');
    }

    public function originPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }

    public function destinationPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }

    public function gateOutScannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gate_out_scanned_by');
    }

    public function gateInScannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gate_in_scanned_by');
    }

    public function eirOut(): HasOne
    {
        return $this->hasOne(BookingContainerEirRecord::class, 'booking_container_unit_id')
            ->where('direction', BookingContainerEirRecord::DIRECTION_OUT);
    }

    public function eirIn(): HasOne
    {
        return $this->hasOne(BookingContainerEirRecord::class, 'booking_container_unit_id')
            ->where('direction', BookingContainerEirRecord::DIRECTION_IN);
    }

    public function vesselVoyage(): BelongsTo
    {
        return $this->belongsTo(VesselVoyage::class, 'vessel_voyage_id');
    }

    public function relayPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'relay_port_id', 'port_id');
    }

    /**
     * SOP Step 9's "In Yard" bar - EIR In done and the line's trip type
     * (from its dispatch document) isn't a foul trip. Mirrors
     * Booking::scopeInYard() but at the single-unit level, since voyage
     * assignment is a per-unit action.
     */
    public function isInYard(): bool
    {
        if (! $this->eirIn()->exists()) {
            return false;
        }

        $tripType = $this->bookingLine?->dispatchDocument?->trip_type;

        return $tripType === null || ! in_array($tripType, ['Tandem Foul', 'Single Foul'], true);
    }

    /**
     * Tightened now that EIR exists (Phase 5): the line's ATW/CAN alone is
     * no longer enough - EIR Out has to be issued too before the unit can
     * physically gate out.
     */
    public function canGateOut(): bool
    {
        return $this->actual_gate_out_at === null
            && $this->bookingLine?->dispatchDocument()->exists()
            && $this->eirOut()->exists();
    }

    public function canGateIn(): bool
    {
        return $this->actual_gate_out_at !== null && $this->actual_gate_in_at === null;
    }

    /**
     * Auto-detects which leg a scan represents, so pier personnel never
     * have to pick a direction themselves. Null means neither transition
     * is valid right now (already fully round-tripped, or the line's
     * dispatch document / EIR Out isn't issued yet).
     */
    public function nextGateAction(): ?string
    {
        if ($this->canGateOut()) {
            return 'OUT';
        }

        if ($this->canGateIn()) {
            return 'IN';
        }

        return null;
    }

    /**
     * GP-OUT-YYYYMM-0001, resetting each month - same lockForUpdate
     * collision pattern as Booking::generateNextCode.
     */
    public static function generateNextGatePassOutNumber(): string
    {
        return DB::transaction(function () {
            $prefix = 'GP-OUT-'.now()->format('Ym').'-';

            $seq = DB::table('booking_container_units')
                ->where('gate_pass_out_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->pluck('gate_pass_out_number')
                ->map(fn ($number) => (int) substr($number, strlen($prefix)))
                ->max() ?? 0;

            do {
                $seq++;
                $candidate = $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            } while (self::where('gate_pass_out_number', $candidate)->exists());

            return $candidate;
        });
    }
}
