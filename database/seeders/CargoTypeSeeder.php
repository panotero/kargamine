<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CargoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Cargo Type']);
        foreach ([
            'General Cargo',
            'Perishable Goods',
            'Fragile / Breakable',
            'Liquid Bulk',
            'Dry Bulk',
            'Machinery & Equipment',
            'Livestock',
            'Documents / Parcels',
            'Frozen / Chilled Goods',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }
    }
}
