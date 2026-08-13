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
     * were removed from the form. A port still referenced elsewhere (lanes,
     * port charges, container assets, ...) fails on delete via its FK
     * restrict constraint - same "protect existing history" behavior as
     * Container::syncCatalog().
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

        $this->ports()->whereNotIn('port_id', $keepIds)->get()->each->delete();
    }

    /**
     * Full sync of this location's serviceable areas from the Location
     * modal, mirroring syncPorts() above.
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

        $this->serviceableAreas()->whereNotIn('area_id', $keepIds)->get()->each->delete();
    }
}
