<?php

namespace Database\Seeders;

use App\Models\CargoYard;
use Illuminate\Database\Seeder;

class CargoYardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Batangas - Bauan',
            'CDO - Villanueva',
            'Cebu - Talisay',
            'Masbate - Mobo',
            "Palawan - Brooke's Point",
            'Palawan - Coron',
            'Palawan - Puerto Princesa',
        ] as $name) {
            CargoYard::firstOrCreate(['name' => $name]);
        }
    }
}
