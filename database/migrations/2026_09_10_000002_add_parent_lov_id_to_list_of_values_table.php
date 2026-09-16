<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nullable self-reference so an LOV group can cascade off another one - e.g.
 * "Industry Sub-Category" values each pointing at the "Industry" value they
 * belong under, the same way ports cascade off locations. Every existing
 * LOV group leaves this null and is unaffected.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('list_of_values_table', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_lov_id')->nullable()->after('lov_optionId');
            $table->foreign('parent_lov_id')->references('lov_id')->on('list_of_values_table')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('list_of_values_table', function (Blueprint $table) {
            $table->dropForeign(['parent_lov_id']);
            $table->dropColumn('parent_lov_id');
        });
    }
};
