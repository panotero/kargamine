<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Industry']);
        foreach ([
            'Manufacturing',
            'Retail',
            'Logistics & Freight',
            'Construction',
            'Agriculture',
            'Information Technology',
            'Food & Beverage',
            'Pharmaceuticals',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }
    }
}
