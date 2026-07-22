<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Addresses now live in crm_lead_addresses (multiple per lead, each with
        // an address_type). Carry over whatever single address each lead already
        // had before dropping the old flat columns below.
        $companies = DB::table('crm_company_info')
            ->whereNotNull('address_no')
            ->orWhereNotNull('address_building')
            ->orWhereNotNull('address_street')
            ->orWhereNotNull('address_barangay')
            ->orWhereNotNull('address_town_city')
            ->orWhereNotNull('address_province')
            ->orWhereNotNull('address_postal_code')
            ->get();

        foreach ($companies as $company) {
            DB::table('crm_lead_addresses')->insert([
                'lead_id' => $company->lead_id,
                'address_type' => null,
                'is_primary' => true,
                'address_no' => $company->address_no,
                'address_building' => $company->address_building,
                'address_street' => $company->address_street,
                'address_barangay' => $company->address_barangay,
                'address_town_city' => $company->address_town_city,
                'address_province' => $company->address_province,
                'address_country' => $company->address_country ?: 'Philippines',
                'address_postal_code' => $company->address_postal_code,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('crm_company_info', function (Blueprint $table) {
            $table->dropColumn([
                'address_no',
                'address_building',
                'address_street',
                'address_barangay',
                'address_town_city',
                'address_province',
                'address_country',
                'address_postal_code',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_company_info', function (Blueprint $table) {
            $table->string('address_no')->nullable();
            $table->string('address_building')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_barangay')->nullable();
            $table->string('address_town_city')->nullable();
            $table->string('address_province')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_postal_code')->nullable();
        });
    }
};
