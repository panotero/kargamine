<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Setting_roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('setting_role')->upsert(
            [
                ['id' => 1, 'role_name' => 'superadmin', 'is_system' => true],
                ['id' => 2, 'role_name' => 'admin', 'is_system' => true],
                ['id' => 3, 'role_name' => 'user', 'is_system' => true],
                ['id' => 4, 'role_name' => 'developer', 'is_system' => true],
            ],
            ['role_name'], // unique key to check
            ['role_name', 'is_system']  // columns to update if exists
        );
    }
}
