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
        Schema::create('nav_icons', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. "home", "users" - what nav_menus.icon stores
            $table->string('label'); // e.g. "Home" - shown in the picker
            $table->text('svg'); // inner <path>/<circle>/... markup, wrapped in a shared <svg> by the frontend
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nav_icons');
    }
};
