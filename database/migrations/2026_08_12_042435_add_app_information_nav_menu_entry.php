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
        // Looked up by title (rather than a hardcoded id) since "Developer Option"'s
        // row id isn't guaranteed to be the same across environments.
        $parentId = DB::table('nav_menus')->where('title', 'Developer Option')->value('id');

        DB::table('nav_menus')->updateOrInsert(
            ['link' => '/page_app_information'],
            [
                'title' => 'Application Information',
                'icon' => 'information-circle',
                'allowed_roles' => json_encode(['4', '1']),
                'parent_menu' => $parentId ? (string) $parentId : '0',
                'menu_order' => '5',
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
        DB::table('nav_menus')->where('link', '/page_app_information')->delete();
    }
};
