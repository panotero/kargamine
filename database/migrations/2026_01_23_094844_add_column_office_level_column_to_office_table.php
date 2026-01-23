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
        Schema::table('office_table', function (Blueprint $table) {
            $table->integer('parent_office_id')->nullable()->after('office_code'); // parent office id
            $table->tinyInteger('office_level')->default(1)->after('parent_office_id'); // hierarchy level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('office', function (Blueprint $table) {
            //
        });
    }
};
