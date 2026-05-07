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
        Schema::create('billed_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('company_info_master')
                ->cascadeOnDelete();

            $table->string('billed_to')->nullable();
            $table->string('company_name')->nullable();
            $table->text('address')->nullable();
            $table->string('tin_no')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billed_details');
    }
};
