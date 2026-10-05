<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets an LOV group flag specific values as "individual" rather than
 * "corporate" - first use is the "Type of Business" list, which now carries
 * the individual/corporate distinction that used to be a separate manual
 * toggle on the Prospect Identity tab (see Prospect::stageCompletionFlags()).
 * Every existing value defaults to false (corporate) and other LOV groups
 * are unaffected.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('list_of_values_table', function (Blueprint $table) {
            $table->boolean('lov_is_individual')->default(false)->after('lov_description');
        });
    }

    public function down(): void
    {
        Schema::table('list_of_values_table', function (Blueprint $table) {
            $table->dropColumn('lov_is_individual');
        });
    }
};
