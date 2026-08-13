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

        User::firstOrCreate(
            ['email' => 'creditofficer@email.com'],
            [
                'name' => 'Credit Officer',
                'password' => Hash::make('Testing123'),
                'role_id' => 5,
                'email_verified_at' => null,
                'remember_token' => null,
                'session_id' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'minton.diaz@email.com'],
            [
                'name' => 'Minton Diaz',
                'password' => Hash::make('Testing123'),
                'role_id' => 4,
                'email_verified_at' => null,
                'remember_token' => null,
                'session_id' => null,
            ]
        );

        // must_change_password is true for these two - they were provisioned with the
        // default seeded password and are expected to set their own on first login.
        User::firstOrCreate(
            ['email' => 'fritzie.tangan@kargamine.com.ph'],
            [
                'name' => 'Fritzie Tangan',
                'password' => Hash::make('Testing123'),
                'role_id' => 2,
                'must_change_password' => true,
                'email_verified_at' => null,
                'remember_token' => null,
                'session_id' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'eden.palma@karga-container.com'],
            [
                'name' => 'Eden Palma',
                'password' => Hash::make('Testing123'),
                'role_id' => 6,
                'must_change_password' => true,
                'email_verified_at' => null,
                'remember_token' => null,
                'session_id' => null,
            ]
        );
    }
}
