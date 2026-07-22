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
            $table->text('industry_description')->nullable()->after('type_of_business');
        });

        // Individual-type leads have no company, so company_name can't stay required.
        Schema::table('crm_company_info', function (Blueprint $table) {
            $table->string('company_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_company_info', function (Blueprint $table) {
            $table->dropColumn('industry_description');
            $table->string('company_name')->nullable(false)->change();
        });
    }
};
