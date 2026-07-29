<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * authorized_signatory_name was already on this table but never
     * rendered anywhere in the UI - replaced here with the same structured
     * name (title/first/middle/last/gender) plus contact details used for
     * the main lead contact, rather than one flat text field.
     * authorized_signatory_position is kept as-is.
     */
    public function up(): void
    {
        Schema::table('crm_company_info', function (Blueprint $table) {
            $table->dropColumn('authorized_signatory_name');

            $table->string('authorized_signatory_title')->nullable()->after('industry_description');
            $table->string('authorized_signatory_first_name')->nullable()->after('authorized_signatory_title');
            $table->string('authorized_signatory_middle_name')->nullable()->after('authorized_signatory_first_name');
            $table->string('authorized_signatory_last_name')->nullable()->after('authorized_signatory_middle_name');
            $table->string('authorized_signatory_gender')->nullable()->after('authorized_signatory_last_name');

            $table->string('authorized_signatory_mobile')->nullable()->after('authorized_signatory_position');
            $table->string('authorized_signatory_mobile_type')->nullable()->after('authorized_signatory_mobile');
            $table->string('authorized_signatory_landline')->nullable()->after('authorized_signatory_mobile_type');
            $table->string('authorized_signatory_landline_type')->nullable()->after('authorized_signatory_landline');
            $table->string('authorized_signatory_email')->nullable()->after('authorized_signatory_landline_type');
            $table->string('authorized_signatory_email_type')->nullable()->after('authorized_signatory_email');
        });
    }

    public function down(): void
    {
        Schema::table('crm_company_info', function (Blueprint $table) {
            $table->dropColumn([
                'authorized_signatory_title',
                'authorized_signatory_first_name',
                'authorized_signatory_middle_name',
                'authorized_signatory_last_name',
                'authorized_signatory_gender',
                'authorized_signatory_mobile',
                'authorized_signatory_mobile_type',
                'authorized_signatory_landline',
                'authorized_signatory_landline_type',
                'authorized_signatory_email',
                'authorized_signatory_email_type',
            ]);

            $table->string('authorized_signatory_name')->nullable()->after('industry_description');
        });
    }
};
