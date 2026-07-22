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
        Schema::create('app_theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('main_color')->default('blue');
            $table->string('accent_color')->default('orange');
            $table->string('button_secondary_color')->default('slate');
            $table->string('button_danger_color')->default('red');
            $table->string('dark_mode')->default('system');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_theme_settings');
    }
};
