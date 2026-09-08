<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vessel extends Model
{
    public const STATUS_ACTIVE = 1;

    public const STATUS_UNDER_REPAIR = 2;

    public const STATUS_OUT_OF_SERVICE = 3;

    public const STATUS_DECOMMISSIONED = 4;

    public const STATUS_LABELS = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_UNDER_REPAIR => 'Under Repair',
        self::STATUS_OUT_OF_SERVICE => 'Out of Service',
        self::STATUS_DECOMMISSIONED => 'Decommissioned',
    ];

    protected $fillable = [
        'name',
        'vessel_code',
        'vessel_type',
        'imo_number',
        'call_sign',
        'mmsi_number',
        'flag_state',
        'status',
        'engine_count',
        'engine_power_hp',
        'date_manufactured',
        'gross_tonnage',
        'deadweight_tonnage',
        'capacity_teu',
        'length_overall_m',
        'beam_m',
        'draft_m',
        'max_speed_knots',
        'home_port_id',
        'owner_operator',
        'classification_society',
        'notes',
    ];

    protected $casts = [
        'status' => 'integer',
        'date_manufactured' => 'date:Y-m-d',
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function homePort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'home_port_id', 'port_id');
    }

    public function voyages(): HasMany
    {
        return $this->hasMany(VesselVoyage::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(VesselStatusHistory::class)->orderByDesc('recorded_at');
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(VesselMaintenanceRecord::class)->orderByDesc('performed_at');
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? (string) $this->status;
    }

    /**
     * Change status and append exactly one status_histories row capturing
     * the transition, mirroring ContainerAsset::applyChange(). No-ops the
     * history write (but still saves) if the status didn't actually change.
     */
    public function applyStatusChange(int $toStatus, ?string $notes, ?int $recordedBy = null): ?VesselStatusHistory
    {
        $fromStatus = $this->status;

        if ($fromStatus === $toStatus) {
            $this->save();

            return null;
        }

        $this->status = $toStatus;
        $this->save();

        return $this->statusHistories()->create([
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $notes,
            'recorded_by' => $recordedBy,
            'recorded_at' => now(),
        ]);
    }
}
