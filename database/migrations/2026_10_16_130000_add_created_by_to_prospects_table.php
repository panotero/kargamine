<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Permanent record of who created a prospect, separate from assigned_to
 * (the currently staffed CSR, set only by Management's deliberate
 * assignOwners() action - never auto-defaulted to the creator anymore).
 * Purely historical - no authorization reads this column.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('relationship_manager_id');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
