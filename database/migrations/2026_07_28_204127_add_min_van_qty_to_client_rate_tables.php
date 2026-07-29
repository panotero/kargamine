<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nullable on both - null means no minimum enforced, so existing rate
     * rows (and any code that doesn't set it) keep behaving exactly as
     * before. Only rows with an explicit min_van_qty gate the discount on
     * the booked quantity meeting it (see RateResolutionService).
     */
    public function up(): void
    {
        Schema::table('client_proposal_rates', function (Blueprint $table) {
            $table->unsignedInteger('min_van_qty')->nullable()->after('container_variant_id');
        });

        Schema::table('client_contract_rates', function (Blueprint $table) {
            $table->unsignedInteger('min_van_qty')->nullable()->after('container_variant_id');
        });
    }

    public function down(): void
    {
        Schema::table('client_proposal_rates', function (Blueprint $table) {
            $table->dropColumn('min_van_qty');
        });

        Schema::table('client_contract_rates', function (Blueprint $table) {
            $table->dropColumn('min_van_qty');
        });
    }
};
