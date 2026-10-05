<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Missed in the original containers migration - the Container form has
 * per-side Dispatch Mode fields (disabled client-side when that side's
 * Service Type is Pier), same single/tandem values as Trucking's
 * dispatch_mode.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_request_product_containers', function (Blueprint $table) {
            $table->string('dispatch_mode_origin')->nullable()->after('destination_port_id');
            $table->string('dispatch_mode_destination')->nullable()->after('dispatch_mode_origin');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_request_product_containers', function (Blueprint $table) {
            $table->dropColumn(['dispatch_mode_origin', 'dispatch_mode_destination']);
        });
    }
};
