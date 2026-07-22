<?php
// database/migrations/2026_07_15_000000_add_lead_id_to_client_proposals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->foreignId('lead_id')->nullable()->after('client_id')
                ->constrained('crm_leads')->nullOnDelete();
        });

        // client_id becomes optional - a proposal can now belong to either
        // a lead (pre-client) or a client (post-conversion), never neither.
        // Requires doctrine/dbal: composer require doctrine/dbal --dev
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lead_id');
            $table->foreignId('client_id')->nullable(false)->change();
        });
    }
};
