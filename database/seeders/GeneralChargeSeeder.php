<?php

namespace Database\Seeders;

use App\Models\ChargeType;
use App\Models\GeneralCharge;
use Illuminate\Database\Seeder;

class GeneralChargeSeeder extends Seeder
{
    private const AMOUNT_BY_CHARGE_CODE = [
        'DOC_FEE' => 250,
        'INS_FEE' => 150,
    ];

    // Fixed (rather than now()->subDay()) so re-seeding on a different day - e.g. production - reproduces this environment's exact rows.
    private const EFFECTIVE_DATE = '2026-07-27';

    public function run(): void
    {
        $chargeTypes = ChargeType::where('applicable_to', ChargeType::APPLICABLE_GENERAL)->get();

        foreach ($chargeTypes as $chargeType) {
            GeneralCharge::updateOrCreate(
                [
                    'charge_type_id' => $chargeType->charge_type_id,
                    'effective_date' => self::EFFECTIVE_DATE,
                ],
                [
                    'amount' => self::AMOUNT_BY_CHARGE_CODE[$chargeType->code] ?? 200,
                    'end_date' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
