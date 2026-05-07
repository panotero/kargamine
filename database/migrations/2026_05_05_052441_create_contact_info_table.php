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
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('company_info_master')
                ->cascadeOnDelete();

            $table->string('contact_name');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();

            $table->string('role')->nullable();     // LOV
            $table->string('position')->nullable(); // LOV

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_info');
    }
};
