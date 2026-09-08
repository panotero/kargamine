<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_lead_containers', function (Blueprint $table) {
            $table->string('cargo_type')->nullable()->after('general_cargo_description');
        });
    }

    public function down(): void
    {
        Schema::table('crm_lead_containers', function (Blueprint $table) {
            $table->dropColumn('cargo_type');
        });
    }
};
