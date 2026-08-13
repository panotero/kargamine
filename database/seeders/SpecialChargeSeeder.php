<?php

namespace Database\Seeders;

use App\Models\SpecialCharge;
use Illuminate\Database\Seeder;

class SpecialChargeSeeder extends Seeder
{
    /**
     * Sample base_value per charge - real rates still need to be reviewed and
     * filled in via the Special Charges tab (App Settings > Rate Maintenance),
     * this just avoids seeding a bare 0 for every row. 'Bullet Seal' keeps the
     * actual value already filled in on the live database.
     */
    private const BASE_VALUE_OVERRIDES = [
        'Amendment Fee' => 1500,
        'Backhoe Rental' => 3500,
        'Bullet Seal' => 1234567.50,
        'Container Van Rental' => 5000,
        'Crane Rental' => 8000,
        'Demurrage' => 2000,
        'Documentation Assistance Fee' => 1000,
        'Double Handling Fee' => 2500,
        'Driver Assistance Fee' => 800,
        'Forklift Rental' => 3000,
        'Foul Trip' => 1500,
        'Genset Rental' => 2500,
        'Hustling' => 1200,
        'Inspection Fee' => 1000,
        'Lashing Fee' => 1500,
        'Lashing Materials' => 800,
        'Lift On Lift Off' => 3500,
        'Loader Rental' => 3000,
        'Overweight Fee' => 2000,
        'Port Charges' => 1500,
        'Reimbursement' => 1000,
        'Storage' => 500,
        'Stripping' => 2500,
        'Stuffing' => 2500,
        'Trucking' => 3500,
        'Valuation Fee' => 1000,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::BASE_VALUE_OVERRIDES as $name => $baseValue) {
            SpecialCharge::firstOrCreate(['name' => $name], ['base_value' => $baseValue]);
        }
    }
}
