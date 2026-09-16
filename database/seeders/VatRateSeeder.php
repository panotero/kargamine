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
     *
     * Tax status list is fixed (not versioned per calendar date the way the
     * old "General" rows were) - VAT-LS/VAT-LG are the only 12% statuses,
     * everything else is 0%.
     */
    private const RATES = [
        ['tax_type' => 'VAT-ZERO-LS', 'effective_date' => '2026-09-08', 'rate_percent' => 0.00],
        ['tax_type' => 'VAT-ZERO-LG', 'effective_date' => '2026-09-08', 'rate_percent' => 0.00],
        ['tax_type' => 'VAT-LS', 'effective_date' => '2026-09-08', 'rate_percent' => 12.00],
        ['tax_type' => 'VAT-LG', 'effective_date' => '2026-09-08', 'rate_percent' => 12.00],
        ['tax_type' => 'VAT-EXEMPT-LS', 'effective_date' => '2026-09-08', 'rate_percent' => 0.00],
        ['tax_type' => 'VAT-EXEMPT-LB', 'effective_date' => '2026-09-08', 'rate_percent' => 0.00],
        ['tax_type' => 'NON-VAT-LS', 'effective_date' => '2026-09-08', 'rate_percent' => 0.00],
        ['tax_type' => 'NON-VAT-LG', 'effective_date' => '2026-09-08', 'rate_percent' => 0.00],
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
