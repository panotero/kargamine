<?php

namespace Database\Seeders;

use App\Models\VatRate;
use Illuminate\Database\Seeder;

class VatRateSeeder extends Seeder
{
    public function run(): void
    {
        VatRate::updateOrCreate(
            ['effective_date' => now()->subDay()->toDateString()],
            ['rate_percent' => 12.00, 'end_date' => null, 'is_active' => true]
        );
    }
}
