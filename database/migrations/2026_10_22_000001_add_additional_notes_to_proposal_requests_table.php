<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * RFP wizard Confirmation tab - free-text additional notes captured at
 * submit time (feature "5b").
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_requests', function (Blueprint $table) {
            // No ->after() - the terms_of_payment_standard/_charter columns
            // this was originally positioned after were already dropped by
            // 2026_10_14_131029_create_proposal_request_terms_of_payment_table.php
            // (replaced by a normalized per-section table), so that column
            // no longer exists by the time this migration runs.
            $table->text('additional_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('proposal_requests', function (Blueprint $table) {
            $table->dropColumn('additional_notes');
        });
    }
};
