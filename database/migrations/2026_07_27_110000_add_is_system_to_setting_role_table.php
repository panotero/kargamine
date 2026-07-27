<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_role', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('role_name');
        });

        // The four originally-seeded roles are relied on throughout the app
        // (NavMenu.allowed_roles arrays, the isSuperAdmin gate) - protect
        // them from deletion via the new Roles CRUD.
        DB::table('setting_role')
            ->whereIn('role_name', ['superadmin', 'admin', 'user', 'developer'])
            ->update(['is_system' => true]);
    }

    public function down(): void
    {
        Schema::table('setting_role', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
};
