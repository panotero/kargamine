<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vat_rates', function (Blueprint $table) {
            $table->string('tax_type')->nullable();
        });

        DB::table('vat_rates')->whereNull('tax_type')->update(['tax_type' => 'General']);

        Schema::table('vat_rates', function (Blueprint $table) {
            $table->dropUnique(['effective_date']);
            $table->unique(['tax_type', 'effective_date']);
        });
    }

    public function down(): void
    {
        Schema::table('vat_rates', function (Blueprint $table) {
            $table->dropUnique(['tax_type', 'effective_date']);
        });

        Schema::table('vat_rates', function (Blueprint $table) {
            $table->unique('effective_date');
        });

        Schema::table('vat_rates', function (Blueprint $table) {
            $table->dropColumn('tax_type');
        });
    }
};
