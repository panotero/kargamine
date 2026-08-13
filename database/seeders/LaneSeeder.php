<?php

namespace Database\Seeders;

use App\Models\Lane;
use App\Models\Port;
use Illuminate\Database\Seeder;

class LaneSeeder extends Seeder
{
    /**
     * A handful of real named ports (see PortSeeder, sourced from the Rate
     * Maintenance Excel via data/locations.json) wired into lanes both
     * ways, so a test booking can pick either port as origin. One port per
     * sample location - Manila, Cebu, Batangas, Davao, Bacolod, CDO.
     */
    public const SAMPLE_PORT_NAMES = [
        'North Harbour', // Manila
        'KTC Port', // Cebu
        'Tabangao Port', // Batangas
        'Sasa Port', // Davao
        'Banogo Power Plant Port', // Bacolod
        'Oro Port', // CDO
    ];

    private const PAIRS = [
        ['North Harbour', 'KTC Port'],
        ['North Harbour', 'Tabangao Port'],
        ['North Harbour', 'Sasa Port'],
        ['North Harbour', 'Banogo Power Plant Port'],
        ['North Harbour', 'Oro Port'],
        ['KTC Port', 'Sasa Port'],
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
