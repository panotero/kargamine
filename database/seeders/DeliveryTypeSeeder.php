<?php

namespace Database\Seeders;

use App\Models\DeliveryType;
use Illuminate\Database\Seeder;

class DeliveryTypeSeeder extends Seeder
{
    /**
     * The 4 combinations of origin/destination mode (Door or Pier) used
     * throughout the Sales & Operations Process - Door-Door/Door-Pier/
     * Pier-Door/Pier-Pier. includes_origin_trucking/includes_destination_
     * trucking are what RateResolutionService actually keys off of; code
     * follows the migration's own DD/DP/PD/PP convention.
     */
    private const TYPES = [
        ['code' => 'DD', 'name' => 'Door-Door', 'includes_origin_trucking' => true, 'includes_destination_trucking' => true],
        ['code' => 'DP', 'name' => 'Door-Pier', 'includes_origin_trucking' => true, 'includes_destination_trucking' => false],
        ['code' => 'PD', 'name' => 'Pier-Door', 'includes_origin_trucking' => false, 'includes_destination_trucking' => true],
        ['code' => 'PP', 'name' => 'Pier-Pier', 'includes_origin_trucking' => false, 'includes_destination_trucking' => false],
    ];

    public function run(): void
    {
        foreach (self::TYPES as $type) {
            DeliveryType::updateOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'includes_origin_trucking' => $type['includes_origin_trucking'],
                    'includes_destination_trucking' => $type['includes_destination_trucking'],
                ]
            );
        }
    }
}
