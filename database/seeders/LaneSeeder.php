<?php

namespace Database\Seeders;

use App\Models\Lane;
use App\Models\Port;
use Illuminate\Database\Seeder;

class LaneSeeder extends Seeder
{
    /**
     * A handful of real named ports (see PortSeeder) wired into lanes both
     * ways, so a test booking can pick either port as origin. Kept to a
     * small, well-known set rather than all 200 seeded ports.
     */
    public const SAMPLE_PORT_NAMES = ['MANILA', 'CEBU', 'BATANGAS', 'DAVAO', 'ILOILO', 'CAGAYAN'];

    private const PAIRS = [
        ['MANILA', 'CEBU'],
        ['MANILA', 'BATANGAS'],
        ['MANILA', 'DAVAO'],
        ['MANILA', 'ILOILO'],
        ['MANILA', 'CAGAYAN'],
        ['CEBU', 'DAVAO'],
    ];

    public function run(): void
    {
        $ports = Port::whereIn('name', self::SAMPLE_PORT_NAMES)->get()->keyBy('name');

        foreach (self::PAIRS as [$originCode, $destinationCode]) {
            foreach ([[$originCode, $destinationCode], [$destinationCode, $originCode]] as [$from, $to]) {
                Lane::updateOrCreate(
                    ['origin_port_id' => $ports[$from]->port_id, 'destination_port_id' => $ports[$to]->port_id],
                    ['is_active' => true]
                );
            }
        }
    }
}
