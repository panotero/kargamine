<?php

namespace Database\Seeders;

use App\Models\HandlingFee;
use App\Models\Port;
use Illuminate\Database\Seeder;

class HandlingFeeSeeder extends Seeder
{
    public function run(): void
    {
        $ports = Port::whereIn('code', LaneSeeder::SAMPLE_PORT_CODES)->get();

        foreach ($ports as $port) {
            HandlingFee::updateOrCreate(
                ['port_id' => $port->port_id, 'effective_date' => now()->subDay()->toDateString()],
                ['amount' => 750, 'end_date' => null, 'is_active' => true]
            );
        }
    }
}
