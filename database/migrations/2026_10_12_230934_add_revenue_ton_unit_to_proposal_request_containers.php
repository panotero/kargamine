<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Revenue Ton (RT) on a Freight/Container row (LC/RC types only) needs its
 * billing basis - CBM (volume) or MT (weight) - same CBM|MT convention
 * already used by the Products tab's Rolling/Loose Cargo forms
 * (proposal_request_product_rolling_cargo/loose_cargo.revenue_ton_unit).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            $table->string('revenue_ton_unit')->nullable()->after('revenue_ton'); // CBM | MT
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            $table->dropColumn('revenue_ton_unit');
        });
    }
};
