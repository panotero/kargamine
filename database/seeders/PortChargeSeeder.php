<?php

namespace Database\Seeders;

use App\Models\ChargeType;
use App\Models\Port;
use App\Models\PortCharge;
use Illuminate\Database\Seeder;

class PortChargeSeeder extends Seeder
{
    private const AMOUNT_BY_CHARGE_CODE = [
        'WHARF' => 500,
        'ARRASTRE' => 800,
        'THC' => 1200,
    ];

    // Fixed (rather than now()->subDay()) so re-seeding on a different day - e.g. production - reproduces this environment's exact rows.
    private const EFFECTIVE_DATE = '2026-07-27';

    public function run(): void
    {
        $ports = Port::whereIn('name', LaneSeeder::SAMPLE_PORT_NAMES)->get();
        $chargeTypes = ChargeType::where('applicable_to', ChargeType::APPLICABLE_PORT)->get();

        foreach ($ports as $port) {
            foreach ($chargeTypes as $chargeType) {
                PortCharge::updateOrCreate(
                    [
                        'port_id' => $port->port_id,
                        'charge_type_id' => $chargeType->charge_type_id,
                        'effective_date' => self::EFFECTIVE_DATE,
                    ],
                    [
                        'amount' => self::AMOUNT_BY_CHARGE_CODE[$chargeType->code] ?? 500,
                        'end_date' => null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
