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
        Schema::table('client_contacts', function (Blueprint $table) {
            $table->dropColumn([
                'contact_name',
                'contact_number',
                'contact_number_type',
                'contact_email',
                'contact_email_type',
                'role',
                'position',
            ]);
        });

        Schema::table('client_contacts', function (Blueprint $table) {
            $table->string('contact_department')->nullable()->after('client_id');
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('position')->nullable();
            $table->string('landline_number')->nullable();
            $table->string('landline_type')->nullable();
            $table->string('mobile')->nullable();
            $table->string('mobile_type')->nullable();
            $table->string('email')->nullable();
            $table->string('email_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_contacts', function (Blueprint $table) {
            $table->dropColumn([
                'contact_department',
                'title',
                'first_name',
                'last_name',
                'gender',
                'position',
                'landline_number',
                'landline_type',
                'mobile',
                'mobile_type',
                'email',
                'email_type',
            ]);
        });

        Schema::table('client_contacts', function (Blueprint $table) {
            $table->string('contact_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_number_type')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_email_type')->nullable();
            $table->string('role')->nullable();
        });
    }
};
