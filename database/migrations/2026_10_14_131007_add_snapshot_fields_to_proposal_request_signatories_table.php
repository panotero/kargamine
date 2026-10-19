<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A signatory used to be a pure live reference to a prospect_contacts row
 * (no snapshot). Now the Authorized Signatory form lets the admin edit the
 * picked contact's details, or skip picking a contact entirely and type
 * everything manually - so prospect_contact_id becomes optional, and a
 * name/position/email/contact_number snapshot is captured on every Add
 * regardless of whether a contact was picked. See
 * ProspectController::submitProposalRequest() for how this snapshot later
 * syncs back into prospect_contacts on submit.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_request_signatories', function (Blueprint $table) {
            $table->dropForeign(['prospect_contact_id']);
        });

        Schema::table('proposal_request_signatories', function (Blueprint $table) {
            $table->foreignId('prospect_contact_id')->nullable()->change();
            $table->foreign('prospect_contact_id')->references('id')->on('prospect_contacts')->cascadeOnDelete();

            $table->string('name')->nullable()->after('prospect_contact_id');
            $table->string('position')->nullable()->after('name');
            $table->string('email')->nullable()->after('position');
            $table->string('contact_number')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_request_signatories', function (Blueprint $table) {
            $table->dropColumn(['name', 'position', 'email', 'contact_number']);
            $table->dropForeign(['prospect_contact_id']);
        });

        Schema::table('proposal_request_signatories', function (Blueprint $table) {
            $table->foreignId('prospect_contact_id')->nullable(false)->change();
            $table->foreign('prospect_contact_id')->references('id')->on('prospect_contacts')->cascadeOnDelete();
        });
    }
};
