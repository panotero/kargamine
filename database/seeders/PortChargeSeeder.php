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

    public function run(): void
    {
        $ports = Port::whereIn('code', LaneSeeder::SAMPLE_PORT_CODES)->get();
        $chargeTypes = ChargeType::where('applicable_to', ChargeType::APPLICABLE_PORT)->get();

        foreach ($ports as $port) {
            foreach ($chargeTypes as $chargeType) {
                PortCharge::updateOrCreate(
                    [
                        'port_id' => $port->port_id,
                        'charge_type_id' => $chargeType->charge_type_id,
                        'effective_date' => now()->subDay()->toDateString(),
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
