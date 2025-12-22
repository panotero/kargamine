<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->json('allowed_office')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->string('allowed_office', 255)->nullable()->change();
        });
    }
};
