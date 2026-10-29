<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Staffing (CSR + Relationship Manager) moves from prospects.assigned_to/
 * relationship_manager_id to here - assigning a Proposal Request should not
 * reassign the prospect itself, and a prospect can legitimately have more
 * than one request over time (see
 * 2026_09_19_000002_allow_multiple_proposal_requests_per_prospect.php), so a
 * single shared prospect-level assignment was wrong once that happens.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_requests', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->foreignId('relationship_manager_id')->nullable()->after('assigned_to')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('proposal_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropConstrainedForeignId('relationship_manager_id');
        });
    }
};
