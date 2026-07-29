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
    /** Base FRT for an unordered port-code pair - same price either direction. */
    private const BASE_RATE_BY_PAIR = [
        'BTG-MNL' => 4000,
        'CEB-MNL' => 9000,
        'CGY-MNL' => 15000,
        'DVO-MNL' => 16000,
        'ILO-MNL' => 8000,
        'CEB-DVO' => 7000,
    ];

    private const CONTAINER_MULTIPLIER = ['CV' => 1.0, 'RF' => 1.4, 'FR' => 1.1];
    private const SIZE_MULTIPLIER = ['20FT' => 1.0, '40FT' => 1.6];
    private const CLASS_MULTIPLIER = ['Standard' => 1.0, 'High Cube' => 1.15];

    public function run(): void
    {
        $ports = Port::whereIn('code', LaneSeeder::SAMPLE_PORT_CODES)->get()->keyBy('port_id');
        $variants = ContainerVariant::with(['container', 'containerClass', 'containerSize'])->get();

        $lanes = Lane::whereIn('origin_port_id', $ports->keys())
            ->whereIn('destination_port_id', $ports->keys())
            ->get();

        foreach ($lanes as $lane) {
            $originCode = $ports[$lane->origin_port_id]->code;
            $destinationCode = $ports[$lane->destination_port_id]->code;
            $pairKey = collect([$originCode, $destinationCode])->sort()->implode('-');
            $baseRate = self::BASE_RATE_BY_PAIR[$pairKey] ?? 10000;

            $tariffRate = LaneTariffRate::firstOrCreate(
                ['lane_id' => $lane->lane_id, 'effective_date' => now()->subDay()->toDateString()],
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
