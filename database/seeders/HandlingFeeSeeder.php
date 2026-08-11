<?php

namespace Database\Seeders;

use App\Models\HandlingFee;
use App\Models\Port;
use Illuminate\Database\Seeder;

class HandlingFeeSeeder extends Seeder
{
    // Fixed (rather than now()->subDay()) so re-seeding on a different day - e.g. production - reproduces this environment's exact rows.
    private const EFFECTIVE_DATE = '2026-07-27';

    public function run(): void
    {
        $ports = Port::whereIn('name', LaneSeeder::SAMPLE_PORT_NAMES)->get();

        foreach ($ports as $port) {
            HandlingFee::updateOrCreate(
                ['port_id' => $port->port_id, 'effective_date' => self::EFFECTIVE_DATE],
                ['amount' => 750, 'end_date' => null, 'is_active' => true]
            );
        }
    }
}
