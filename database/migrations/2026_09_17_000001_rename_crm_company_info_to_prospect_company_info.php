<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('crm_company_info', 'prospect_company_info');

        Schema::table('prospect_company_info', function (Blueprint $table) {
            $table->renameColumn('lead_id', 'prospect_id');
        });
    }

    public function down(): void
    {
        Schema::table('prospect_company_info', function (Blueprint $table) {
            $table->renameColumn('prospect_id', 'lead_id');
        });

        Schema::rename('prospect_company_info', 'crm_company_info');
    }
};
