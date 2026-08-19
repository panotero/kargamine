<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Port;
use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    /**
     * Seeds real Locations + Ports from the Rate Maintenance Excel export
     * (database/seeders/data/locations.json is the single source of truth
     * - see ServiceableAreaSeeder for the matching serviceable-area data).
     * Only the 22 real port locations from the Excel's "PORT LOCATION"
     * column are seeded here - trucking-zone province labels from the
     * "SERVICEABLE AREA" column (e.g. Laguna, Rizal, Cavite) are folded
     * into whichever real port location actually services them, rather
     * than becoming standalone locations of their own.
     */
    public function run(): void
    {
        $data = json_decode(
            file_get_contents(__DIR__ . '/data/locations.json'),
            associative: true
        );

        foreach ($data['locations'] as $locationData) {
            $location = Location::updateOrCreate(
                ['name' => $locationData['name']],
                ['is_active' => true]
            );

            foreach ($locationData['ports'] as $portName) {
                Port::updateOrCreate(
                    ['location_id' => $location->location_id, 'name' => $portName],
                    ['is_active' => true]
                );
            }
        }
    }
}
