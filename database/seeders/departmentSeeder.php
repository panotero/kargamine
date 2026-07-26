<?php

namespace Database\Seeders;

use App\Models\UserDepartment;
use Illuminate\Database\Seeder;

class departmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Sales Department',
            'Operations Department',
        ];

        foreach ($departments as $name) {
            UserDepartment::firstOrCreate(['name' => $name]);
        }
    }
}
