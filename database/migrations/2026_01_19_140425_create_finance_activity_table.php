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

        Schema::create('finance_activity', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp')->useCurrent(); // default to current timestamp
            $table->string('activity', 255);
            $table->text('remarks')->nullable();
            $table->string('status', 50)->default('pending'); // default value can be changed
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_activity');
    }
};
