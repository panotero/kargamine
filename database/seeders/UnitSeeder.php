<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Unit']);
        foreach ([
            'cbm/s',
            'day/s',
            'hour/s',
            'liter/s',
            'month/s',
            'move/s',
            'occurrence/s',
            'others',
            'piece/s',
            'service/s',
            'set/s',
            'ton/s',
            'trip/s',
            'unit/s',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }
    }
}
