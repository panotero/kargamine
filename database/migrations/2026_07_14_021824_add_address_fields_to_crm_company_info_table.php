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
        Schema::table('crm_company_info', function (Blueprint $table) {
            $table->string('address_no')->nullable()->after('company_address');
            $table->string('address_building')->nullable()->after('address_no');
            $table->string('address_street')->nullable()->after('address_building');
            $table->string('address_barangay')->nullable()->after('address_street');
            $table->string('address_town_city')->nullable()->after('address_barangay');
            $table->string('address_province')->nullable()->after('address_town_city');
            $table->string('address_country')->nullable()->after('address_province');
            $table->string('address_postal_code')->nullable()->after('address_country');
            $table->string('type_of_business')->nullable()->after('address_postal_code'); // LOV
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_company_info', function (Blueprint $table) {
            //
        });
    }
};
