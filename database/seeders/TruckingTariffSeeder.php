<?php

namespace Database\Seeders;

use App\Models\DeliveryType;
use App\Models\Port;
use App\Models\ServiceableArea;
use App\Models\TruckingTariff;
use Illuminate\Database\Seeder;

class TruckingTariffSeeder extends Seeder
{
    /**
     * Only delivery types with at least one trucking leg need a tariff row
     * - Pier-Pier (PP) never triggers a trucking lookup in
     * RateResolutionService, so it's skipped here.
     */
    private const TRUCKED_DELIVERY_CODES = ['DD', 'DP', 'PD'];

    // Fixed (rather than now()->subDay()) so re-seeding on a different day - e.g. production - reproduces this environment's exact rows.
    private const EFFECTIVE_DATE = '2026-07-27';

    public function run(): void
    {
        $locationIds = Port::whereIn('name', LaneSeeder::SAMPLE_PORT_NAMES)->pluck('location_id');
        $areas = ServiceableArea::whereIn('location_id', $locationIds)->get();
        $deliveryTypes = DeliveryType::whereIn('code', self::TRUCKED_DELIVERY_CODES)->get();

        foreach ($areas as $area) {
            foreach ($deliveryTypes as $deliveryType) {
                TruckingTariff::updateOrCreate(
                    [
                        'area_id' => $area->area_id,
                        'delivery_type_id' => $deliveryType->delivery_type_id,
                        'effective_date' => self::EFFECTIVE_DATE,
                    ],
                    ['amount' => 1500, 'end_date' => null, 'is_active' => true]
                );
            }
        }
    }
}
