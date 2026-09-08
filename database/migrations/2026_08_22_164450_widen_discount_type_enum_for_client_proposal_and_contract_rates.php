<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE client_proposal_rates MODIFY discount_type ENUM('percentage', 'fixed', 'increase_percentage', 'increase_fixed') NULL");
        DB::statement("ALTER TABLE client_contract_rates MODIFY discount_type ENUM('percentage', 'fixed', 'increase_percentage', 'increase_fixed') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE client_proposal_rates MODIFY discount_type ENUM('percentage', 'fixed') NULL");
        DB::statement("ALTER TABLE client_contract_rates MODIFY discount_type ENUM('percentage', 'fixed') NULL");
    }
};
