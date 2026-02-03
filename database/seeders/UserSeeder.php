<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@email.com'], // search condition
            [
                'name' => 'Developer',
                'password' => Hash::make('Testing123'),
                'role_id' => 1,
                'email_verified_at' => null,
                'remember_token' => null,
                'session_id' => null,
            ]
        );
    }
}
