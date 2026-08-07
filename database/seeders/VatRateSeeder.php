<?php

namespace Database\Seeders;

use App\Models\VatRate;
use Illuminate\Database\Seeder;

class VatRateSeeder extends Seeder
{
    public function run(): void
    {
        VatRate::updateOrCreate(
            ['tax_type' => 'General', 'effective_date' => now()->subDay()->toDateString()],
            ['rate_percent' => 12.00, 'end_date' => null, 'is_active' => true]
        );

        VatRate::updateOrCreate(
            ['tax_type' => 'VAT Inclusive', 'effective_date' => now()->toDateString()],
            ['rate_percent' => 12.00, 'end_date' => null, 'is_active' => true]
        );

        VatRate::updateOrCreate(
            ['tax_type' => 'VAT Exempt', 'effective_date' => now()->toDateString()],
            ['rate_percent' => 0.00, 'end_date' => null, 'is_active' => true]
        );

        VatRate::updateOrCreate(
            ['tax_type' => 'Non-VAT', 'effective_date' => now()->toDateString()],
            ['rate_percent' => 3.00, 'end_date' => null, 'is_active' => true]
        );
    }
}
