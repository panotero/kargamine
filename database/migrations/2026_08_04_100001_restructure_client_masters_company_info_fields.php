<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->dropColumn([
                'contact_number_1',
                'contact_number_2',
                'organization_type',
                'tin',
                'business_start_date',
                'estimated_annual_revenue',
                'company_url',
            ]);
            $table->string('client_category')->nullable()->after('client_mnemonic');
            $table->string('client_classification')->nullable()->after('client_category');
        });
    }

    public function down(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->dropColumn(['client_category', 'client_classification']);
            $table->string('contact_number_1')->nullable();
            $table->string('contact_number_2')->nullable();
            $table->string('organization_type')->nullable();
            $table->string('tin')->nullable();
            $table->date('business_start_date')->nullable();
            $table->decimal('estimated_annual_revenue', 15, 2)->nullable();
            $table->string('company_url')->nullable();
        });
    }
};
