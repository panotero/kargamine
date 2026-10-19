<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Simple maintenance catalog of charter services, same shape as CargoYard/
 * SpecialCharge - a plain name + is_active toggle, no versioning.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charter_services', function (Blueprint $table) {
            $table->id('charter_service_id');
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charter_services');
    }
};
