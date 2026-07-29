<?php

namespace Database\Seeders;

use App\Models\ChargeType;
use Illuminate\Database\Seeder;

class ChargeTypeSeeder extends Seeder
{
    private const TYPES = [
        ['code' => 'WHARF', 'name' => 'Wharfage', 'applicable_to' => ChargeType::APPLICABLE_PORT],
        ['code' => 'ARRASTRE', 'name' => 'Arrastre Charge', 'applicable_to' => ChargeType::APPLICABLE_PORT],
        ['code' => 'THC', 'name' => 'Terminal Handling Charge', 'applicable_to' => ChargeType::APPLICABLE_PORT],
        ['code' => 'DOC_FEE', 'name' => 'Documentation Fee', 'applicable_to' => ChargeType::APPLICABLE_GENERAL],
        ['code' => 'INS_FEE', 'name' => 'Insurance Fee', 'applicable_to' => ChargeType::APPLICABLE_GENERAL],
    ];

    public function run(): void
    {
        foreach (self::TYPES as $type) {
            ChargeType::updateOrCreate(
                ['code' => $type['code']],
                ['name' => $type['name'], 'applicable_to' => $type['applicable_to'], 'is_active' => true]
            );
        }
    }
}
