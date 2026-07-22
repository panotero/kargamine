<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // New "Theme" page under the existing "Developer Option" parent
        // (parent_menu '3', same as the existing Mailer/Menus siblings).
        DB::table('nav_menus')->updateOrInsert(
            ['link' => '/page_theme'],
            [
                'title' => 'Theme',
                'icon' => '',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '3',
                'menu_order' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('nav_menus')->where('link', '/page_theme')->delete();
    }
};
