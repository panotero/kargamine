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

    public function run(): void
    {
        $chargeTypes = ChargeType::where('applicable_to', ChargeType::APPLICABLE_GENERAL)->get();

        foreach ($chargeTypes as $chargeType) {
            GeneralCharge::updateOrCreate(
                [
                    'charge_type_id' => $chargeType->charge_type_id,
                    'effective_date' => now()->subDay()->toDateString(),
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
