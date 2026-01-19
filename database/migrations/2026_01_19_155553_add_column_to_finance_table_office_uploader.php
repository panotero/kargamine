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
        Schema::table('finance', function (Blueprint $table) {
            $table->text('uploading_office')->after('id');
            $table->text('uploaded_by')->after('uploading_office');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_table_office_uploader', function (Blueprint $table) {
            //
        });
    }
};
