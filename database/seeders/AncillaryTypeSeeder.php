<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AncillaryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Ancillary Type']);
        foreach (['Trucking', 'Storage', 'Loading/Unloading', 'Fumigation', 'Weighing', 'Documentation', 'Insurance', 'Others'] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3))]);
        }
    }
}
