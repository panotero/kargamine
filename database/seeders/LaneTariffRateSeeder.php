<?php

namespace Database\Seeders;

use App\Models\ContainerVariant;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;
use App\Models\Port;
use Illuminate\Database\Seeder;

class LaneTariffRateSeeder extends Seeder
{
    /** Base FRT for an unordered port-name pair - same price either direction. */
    private const BASE_RATE_BY_PAIR = [
        'BATANGAS-MANILA' => 4000,
        'CEBU-MANILA' => 9000,
        'CAGAYAN-MANILA' => 15000,
        'DAVAO-MANILA' => 16000,
        'ILOILO-MANILA' => 8000,
        'CEBU-DAVAO' => 7000,
    ];

    private const CONTAINER_MULTIPLIER = ['CV' => 1.0, 'RF' => 1.4, 'FR' => 1.1];

    private const SIZE_MULTIPLIER = ['20FT' => 1.0, '40FT' => 1.6];

    private const CLASS_MULTIPLIER = ['Standard' => 1.0, 'High Cube' => 1.15];

    // Fixed (rather than now()->subDay()) so re-seeding on a different day - e.g. production - reproduces this environment's exact rows.
    private const EFFECTIVE_DATE = '2026-07-27';

    public function run(): void
    {
        $ports = Port::whereIn('name', LaneSeeder::SAMPLE_PORT_NAMES)->get()->keyBy('port_id');
        $variants = ContainerVariant::with(['container', 'containerClass', 'containerSize'])->get();

        $lanes = Lane::whereIn('origin_port_id', $ports->keys())
            ->whereIn('destination_port_id', $ports->keys())
            ->get();

        foreach ($lanes as $lane) {
            $originCode = $ports[$lane->origin_port_id]->name;
            $destinationCode = $ports[$lane->destination_port_id]->name;
            $pairKey = collect([$originCode, $destinationCode])->sort()->implode('-');
            $baseRate = self::BASE_RATE_BY_PAIR[$pairKey] ?? 10000;

            $tariffRate = LaneTariffRate::firstOrCreate(
                ['lane_id' => $lane->lane_id, 'effective_date' => self::EFFECTIVE_DATE],
                ['end_date' => null, 'is_active' => true]
            );

            foreach ($variants as $variant) {
                $containerMultiplier = self::CONTAINER_MULTIPLIER[$variant->container->code] ?? 1.0;
                $sizeMultiplier = self::SIZE_MULTIPLIER[$variant->containerSize->size] ?? 1.0;
                $classMultiplier = self::CLASS_MULTIPLIER[$variant->containerClass->class] ?? 1.0;

                $frt = round($baseRate * $containerMultiplier * $sizeMultiplier * $classMultiplier / 100) * 100;

                LaneTariffRatePrice::updateOrCreate(
                    ['lane_tariff_rate_id' => $tariffRate->rate_id, 'container_variant_id' => $variant->id],
                    ['frt' => $frt]
                );
            }
        }
    }
}
