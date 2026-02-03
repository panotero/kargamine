<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(UserSeeder::class);
        $this->call([
            // DocumentsTableSeeder::class,
            // OfficesTableSeeder::class,
            // FilesTableSeeder::class,
            // ModificationsTableSeeder::class,
            // NotificationsTableSeeder::class,
            NavMenuSeeder::class,
            UserSeeder::class,
            // ThemeSeeder::class,
            Setting_roleSeeder::class
        ]);
    }
}
