<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks the Relationship Manager's forward-approval separately from the
 * final decided_by/decided_at/decision_remarks columns, which now record
 * whichever stage (RM or Manager) made the terminal call - see
 * ClientProposal::canBeApprovedByRm()/canBeApprovedByManager().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->unsignedBigInteger('rm_approved_by')->nullable()->after('decision_remarks');
            $table->timestamp('rm_approved_at')->nullable()->after('rm_approved_by');
            $table->foreign('rm_approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->dropForeign(['rm_approved_by']);
            $table->dropColumn(['rm_approved_by', 'rm_approved_at']);
        });
    }
};
