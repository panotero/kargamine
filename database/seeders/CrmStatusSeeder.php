<?php

namespace Database\Seeders;

use App\Models\CrmStatus;
use Illuminate\Database\Seeder;

class CrmStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'status' => 'LEAD',
                'description' => 'New incoming lead',
            ],
            [
                'status' => 'QUALIFIED',
                'description' => 'Lead is qualified and potential',
            ],
            [
                'status' => 'OPPORTUNITY',
                'description' => 'Converted into sales opportunity',
            ],
            [
                'status' => 'NEGOTIATION',
                'description' => 'In negotiation stage',
            ],
            [
                'status' => 'WIN',
                'description' => 'Final stage: won or lost',
            ],
            [
                'status' => 'LOST',
                'description' => 'Final stage: won or lost',
            ],
        ];

        foreach ($statuses as $status) {
            CrmStatus::updateOrCreate(
                ['status' => $status['status']],
                ['description' => $status['description']]
            );
        }
    }
}
