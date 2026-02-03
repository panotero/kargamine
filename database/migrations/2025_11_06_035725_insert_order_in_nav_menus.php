<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::hasColumn('nav_menus', 'menu_order')) {
            Schema::table('nav_menus', function (Blueprint $table) {
                $table->integer('menu_order')
                    ->default(0)
                    ->after('parent_menu');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            //
        });
    }
};
