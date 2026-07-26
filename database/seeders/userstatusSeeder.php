<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Seeder;

class userstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Active', 'Inactive', 'Suspended', 'Pending'];

        foreach ($statuses as $name) {
            UserStatus::firstOrCreate(['name' => $name]);
        }
    }
}
