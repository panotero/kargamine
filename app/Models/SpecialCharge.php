<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecialCharge extends Model
{
    protected $primaryKey = 'special_charge_id';

    protected $fillable = [
        'name',
        'base_value',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'base_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(SpecialChargeRate::class, 'special_charge_id', 'special_charge_id');
    }

    /**
     * Full sync of this charge's per-unit rates from the Special Charge
     * form - update existing rows (by id), create new ones, and delete
     * rows that were removed from the form. Mirrors
     * Location::syncPorts()/syncServiceableAreas().
     *
     * @param  array<int, array{id?: int|null, unit: string, base_value: float}>  $rates
     */
    public function syncRates(array $rates): void
    {
        $keepIds = [];

        foreach ($rates as $rateData) {
            $rate = $this->rates()->updateOrCreate(
                ['id' => $rateData['id'] ?? null],
                ['unit' => $rateData['unit'], 'base_value' => $rateData['base_value']]
            );
            $keepIds[] = $rate->id;
        }

        $this->rates()->whereNotIn('id', $keepIds)->get()->each->delete();
    }
}
