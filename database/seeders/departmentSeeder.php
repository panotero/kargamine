<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserDepartment;


class departmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $departments = [
            [
                'name' => 'Sales Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operations Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        UserDepartment::insert($departments);
    }
}
