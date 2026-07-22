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
        Schema::create('crm_lead_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_id')
                ->constrained('crm_leads')
                ->cascadeOnDelete();

            $table->string('address_type')->nullable(); // LOV: Office/Warehouse/Branch/Storage Facility
            $table->boolean('is_primary')->default(false);

            $table->string('address_no')->nullable();
            $table->string('address_building')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_barangay')->nullable();
            $table->string('address_town_city')->nullable();
            $table->string('address_province')->nullable();
            $table->string('address_country')->default('Philippines');
            $table->string('address_postal_code')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_lead_addresses');
    }
};
