<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('options_table', function (Blueprint $table) {
            $table->id('option_id');
            $table->string('option_name')->unique();
            $table->text('option_description')->nullable();

            $table->timestamps();

            // Index for fast lookup
            $table->index('option_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options_table');
    }
};
