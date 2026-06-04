<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CrmStatus;

class CrmStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        CrmStatus::truncate();

        CrmStatus::insert([
            [
                'status' => 'LEAD',
                'description' => 'New incoming lead'
            ],
            [
                'status' => 'QUALIFIED',
                'description' => 'Lead is qualified and potential'
            ],
            [
                'status' => 'OPPORTUNITY',
                'description' => 'Converted into sales opportunity'
            ],
            [
                'status' => 'NEGOTIATION',
                'description' => 'In negotiation stage'
            ],
            [
                'status' => 'WIN',
                'description' => 'Final stage: won or lost'
            ],
            [
                'status' => 'LOST',
                'description' => 'Final stage: won or lost'
            ],
        ]);
    }
}
