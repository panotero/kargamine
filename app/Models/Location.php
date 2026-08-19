<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $primaryKey = 'location_id';

    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class, 'location_id', 'location_id');
    }

    public function serviceableAreas(): HasMany
    {
        return $this->hasMany(ServiceableArea::class, 'location_id', 'location_id');
    }

    /**
     * Full sync of this location's ports from the Location modal - update
     * existing rows (by port_id), create new ones, and delete rows that
     * were removed from the form. A dropped port takes its port charges and
     * handling fees with it (those are just rate-setting entries, not a
     * record of an actual transaction), but still fails on delete via its
     * FK restrict constraint if it's referenced by a lane, a serviceable
     * area still in the form's kept list, a booking, a proposal/contract
     * line, a vessel voyage, or container asset history - that's real
     * operational/financial history and stays protected. Call
     * syncServiceableAreas() before this so an area being dropped alongside
     * its port doesn't itself block the port's deletion. Same "protect
     * existing history" behavior as Container::syncCatalog().
     *
     * @param  array<int, array{port_id?: int|null, name: string, is_active?: bool}>  $ports
     */
    public function syncPorts(array $ports): void
    {
        $keepIds = [];

        foreach ($ports as $portData) {
            $port = $this->ports()->updateOrCreate(
                ['port_id' => $portData['port_id'] ?? null],
                ['name' => $portData['name'], 'is_active' => $portData['is_active'] ?? true]
            );
            $keepIds[] = $port->port_id;
        }

        $droppedPortIds = $this->ports()->whereNotIn('port_id', $keepIds)->pluck('port_id');

        PortCharge::whereIn('port_id', $droppedPortIds)->delete();
        HandlingFee::whereIn('port_id', $droppedPortIds)->delete();

        $this->ports()->whereIn('port_id', $droppedPortIds)->get()->each->delete();
    }

    /**
     * Full sync of this location's serviceable areas from the Location
     * modal, mirroring syncPorts() above - a dropped area takes its
     * trucking tariffs with it, but stays protected if it's referenced by a
     * booking or booking line.
     *
     * @param  array<int, array{area_id?: int|null, area_name: string, is_active?: bool}>  $areas
     */
    public function syncServiceableAreas(array $areas): void
    {
        $keepIds = [];

        foreach ($areas as $areaData) {
            $area = $this->serviceableAreas()->updateOrCreate(
                ['area_id' => $areaData['area_id'] ?? null],
                ['area_name' => $areaData['area_name'], 'is_active' => $areaData['is_active'] ?? true]
            );
            $keepIds[] = $area->area_id;
        }

        $droppedAreaIds = $this->serviceableAreas()->whereNotIn('area_id', $keepIds)->pluck('area_id');

        TruckingTariff::whereIn('area_id', $droppedAreaIds)->delete();

        $this->serviceableAreas()->whereIn('area_id', $droppedAreaIds)->get()->each->delete();
    }
}
