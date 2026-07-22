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
        // "Lookup Values" was its own nav item pointing at /page_lookupValues,
        // which is now a "General Lookups" tab inside the Rate Maintenance
        // page (/page_maintenance) instead. NavMenuSeeder no longer seeds it.
        DB::table('nav_menus')->where('link', '/page_lookupValues')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('nav_menus')->insert([
            'title' => 'Lookup Values',
            'icon' => '',
            'link' => '/page_lookupValues',
            'allowed_roles' => json_encode(['1']),
            'parent_menu' => '3',
            'menu_order' => '3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
