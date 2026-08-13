<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\ServiceableArea;
use Illuminate\Database\Seeder;

class ServiceableAreaSeeder extends Seeder
{
    /**
     * Seeds real serviceable areas (trucking zones) per Location from the
     * Rate Maintenance Excel export - see PortSeeder for the matching
     * location/port data, both sourced from data/locations.json.
     */
    public function run(): void
    {
        $data = json_decode(
            file_get_contents(__DIR__.'/data/locations.json'),
            associative: true
        );

        foreach ($data['locations'] as $locationData) {
            $location = Location::where('name', $locationData['name'])->firstOrFail();

            foreach ($locationData['serviceable_areas'] as $areaName) {
                ServiceableArea::updateOrCreate(
                    ['location_id' => $location->location_id, 'area_name' => $areaName],
                    ['is_active' => true]
                );
            }
        }
    }
}
