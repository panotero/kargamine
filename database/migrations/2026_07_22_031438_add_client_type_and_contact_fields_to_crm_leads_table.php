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
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('client_type')->nullable()->after('status'); // individual|corporate
            $table->string('landline_number')->nullable()->after('mobile');
            $table->string('mobile_type')->nullable()->after('landline_number'); // personal|business
            $table->string('landline_type')->nullable()->after('mobile_type'); // personal|business
            $table->string('email_type')->nullable()->after('email'); // personal|business
        });

        // Existing leads all have a company_name, so they're corporate by shape.
        // Without this, the new stage-1 completion check would demote every
        // already-complete lead back to incomplete.
        DB::table('crm_leads')->whereNull('client_type')->update(['client_type' => 'corporate']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn(['client_type', 'landline_number', 'mobile_type', 'landline_type', 'email_type']);
        });
    }
};
