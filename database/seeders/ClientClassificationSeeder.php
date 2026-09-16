<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClientClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Client Classification']);
        foreach ([
            'Shipper',
            'Consignee',
            'Shipper/Consignee (both)',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }
    }
}
