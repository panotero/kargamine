<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ownership record for a prospect, separate from created_by (permanent,
 * historical, never changes) and assigned_to (the currently staffed CSR).
 * owner_id starts equal to the creator and is the column authorization
 * checks (Prospect::canBeEditedBy()/canBeReassignedBy()) read going
 * forward - it only ever changes via a deliberate reassignment action.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->after('created_by');
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
        });

        DB::table('prospects')->whereNull('owner_id')->update(['owner_id' => DB::raw('created_by')]);
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }
};
