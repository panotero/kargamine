<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets a top-level nav_menus row be grouped under a label in the sidebar
 * (see resources/js/navmenu.js) - a plain free-text value, not a lookup
 * table, since the admin form (menus.blade.php) just offers existing
 * values already used elsewhere in this same column as suggestions rather
 * than enforcing a closed list. Nullable/unset = today's ungrouped
 * behavior, unchanged. Only meaningful on parent_menu = 0 rows - a child
 * always renders nested under its own parent's button regardless of any
 * category value, so the admin form hides/ignores this field for children
 * entirely (never sent, never read).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
