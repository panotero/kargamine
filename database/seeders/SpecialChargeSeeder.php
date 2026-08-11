<?php

namespace Database\Seeders;

use App\Models\SpecialCharge;
use Illuminate\Database\Seeder;

class SpecialChargeSeeder extends Seeder
{
    /** Overrides for charges whose base_value has since been filled in via the Special Charges tab. */
    private const BASE_VALUE_OVERRIDES = [
        'Bullet Seal' => 1234567.50,
    ];

    /**
     * Run the database seeds.
     *
     * base_value is seeded at 0 as a placeholder - actual rates need to be
     * filled in via the Special Charges tab (App Settings > Rate Maintenance).
     */
    public function run(): void
    {
        foreach ([
            'Amendment Fee',
            'Backhoe Rental',
            'Bullet Seal',
            'Container Van Rental',
            'Crane Rental',
            'Demurrage',
            'Documentation Assistance Fee',
            'Double Handling Fee',
            'Driver Assistance Fee',
            'Forklift Rental',
            'Foul Trip',
            'Genset Rental',
            'Hustling',
            'Inspection Fee',
            'Lashing Fee',
            'Lashing Materials',
            'Lift On Lift Off',
            'Loader Rental',
            'Overweight Fee',
            'Port Charges',
            'Reimbursement',
            'Storage',
            'Stripping',
            'Stuffing',
            'Trucking',
            'Valuation Fee',
        ] as $name) {
            SpecialCharge::firstOrCreate(['name' => $name], ['base_value' => self::BASE_VALUE_OVERRIDES[$name] ?? 0]);
        }
    }
}
