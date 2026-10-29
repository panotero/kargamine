<?php

namespace Database\Seeders;

use App\Models\Port;
use App\Models\ServiceableArea;
use App\Models\TruckingTariff;
use Illuminate\Database\Seeder;

class TruckingTariffSeeder extends Seeder
{
    // Fixed (rather than now()->subDay()) so re-seeding on a different day - e.g. production - reproduces this environment's exact rows.
    private const EFFECTIVE_DATE = '2026-07-27';

    /**
     * A trucking tariff is "cost to truck between this Serviceable Area and
     * this Port" - the same amount applies whether that door leg is the
     * origin or destination, so there's one row per (area, port) pair
     * within a Location, not per delivery type. Pairs every sample area
     * with every port in its own Location.
     */
    public function run(): void
    {
        $locationIds = Port::whereIn('name', LaneSeeder::SAMPLE_PORT_NAMES)->pluck('location_id');
        $areas = ServiceableArea::whereIn('location_id', $locationIds)->get();
        $portsByLocation = Port::whereIn('location_id', $locationIds)->get()->groupBy('location_id');

        foreach ($areas as $area) {
            foreach ($portsByLocation->get($area->location_id, collect()) as $port) {
                TruckingTariff::updateOrCreate(
                    [
                        'area_id' => $area->area_id,
                        'port_id' => $port->port_id,
                        'effective_date' => self::EFFECTIVE_DATE,
                    ],
                    ['amount' => 1500, 'end_date' => null, 'is_active' => true]
                );
            }
        }
    }
}
