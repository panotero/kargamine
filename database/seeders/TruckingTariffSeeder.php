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

    public function run(): void
    {
        $portIds = Port::whereIn('code', LaneSeeder::SAMPLE_PORT_CODES)->pluck('port_id');
        $areas = ServiceableArea::whereIn('port_id', $portIds)->get();
        $deliveryTypes = DeliveryType::whereIn('code', self::TRUCKED_DELIVERY_CODES)->get();

        foreach ($areas as $area) {
            foreach ($deliveryTypes as $deliveryType) {
                TruckingTariff::updateOrCreate(
                    [
                        'area_id' => $area->area_id,
                        'delivery_type_id' => $deliveryType->delivery_type_id,
                        'effective_date' => now()->subDay()->toDateString(),
                    ],
                    ['amount' => 1500, 'end_date' => null, 'is_active' => true]
                );
            }
        }
    }
}
