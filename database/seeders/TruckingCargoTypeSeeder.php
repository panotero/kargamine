<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TruckingCargoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Trucking Cargo Type']);
        foreach (['20-Footer GP', '20-Footer HC', '40-Footer GP', '40-Footer HC', 'Reefer Van', 'Flatrack', 'Rolling Cargo', 'Loose Cargo', 'Others'] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3))]);
        }
    }
}
