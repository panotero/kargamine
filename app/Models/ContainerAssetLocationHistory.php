<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContainerAssetLocationHistory extends Model
{
    protected $table = 'container_asset_location_history';

    public const SOURCE_MANUAL_RELOCATION = 'manual_relocation';

    public const SOURCE_PIER_CHECKIN = 'pier_checkin';

    public const SOURCE_BOOKING_ASSIGNMENT = 'booking_assignment';

    public const SOURCE_BOOKING_RELEASE = 'booking_release';

    protected $fillable = [
        'container_asset_id',
        'port_id',
        'pier_reference',
        'status_at_time',
        'source',
        'recorded_by',
        'recorded_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'recorded_at' => 'datetime:M d, Y, h:i A',
    ];

    public function containerAsset(): BelongsTo
    {
        return $this->belongsTo(ContainerAsset::class);
    }

    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
