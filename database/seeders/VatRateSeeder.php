<?php

namespace Database\Seeders;

use App\Models\VatRate;
use Illuminate\Database\Seeder;

class VatRateSeeder extends Seeder
{
    /**
     * Fixed effective dates (rather than now()/now()->subDay()) so re-seeding
     * on a different calendar day - e.g. production - reproduces the exact
     * same rows as this environment instead of drifting into new ones.
     */
    private const RATES = [
        ['tax_type' => 'General', 'effective_date' => '2026-07-02', 'rate_percent' => 20.00],
        ['tax_type' => 'General', 'effective_date' => '2026-07-27', 'rate_percent' => 12.00],
        ['tax_type' => 'General', 'effective_date' => '2026-08-05', 'rate_percent' => 12.00],
        ['tax_type' => 'VAT Inclusive', 'effective_date' => '2026-08-06', 'rate_percent' => 12.00],
        ['tax_type' => 'VAT Exempt', 'effective_date' => '2026-08-06', 'rate_percent' => 0.00],
        ['tax_type' => 'Non-VAT', 'effective_date' => '2026-08-06', 'rate_percent' => 3.00],
    ];

    public function run(): void
    {
        foreach (self::RATES as $rate) {
            VatRate::updateOrCreate(
                ['tax_type' => $rate['tax_type'], 'effective_date' => $rate['effective_date']],
                ['rate_percent' => $rate['rate_percent'], 'end_date' => null, 'is_active' => true]
            );
        }
    }
}
