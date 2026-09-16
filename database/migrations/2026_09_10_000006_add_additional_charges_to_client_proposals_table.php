<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Proposal-wide, optional opt-ins - apply once to the whole proposal, not
 * per container line (unlike Ancillary Services, which are per-rate).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->boolean('include_special_charges')->default(false)->after('signature_requested_at');
            $table->boolean('include_port_charges')->default(false)->after('include_special_charges');
            $table->boolean('include_handling_fee')->default(false)->after('include_port_charges');
            $table->boolean('include_general_charges')->default(false)->after('include_handling_fee');
        });
    }

    public function down(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->dropColumn([
                'include_special_charges',
                'include_port_charges',
                'include_handling_fee',
                'include_general_charges',
            ]);
        });
    }
};
