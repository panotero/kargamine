<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospect_contact_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_contact_id')->constrained('prospect_contacts')->cascadeOnDelete();
            // cascadeOnDelete (not restrict): prospect_locations rows are
            // recreated (new ids) on every Identity save (delete-then-
            // recreate in ProspectController::saveStage1), so a
            // contact->location link is inherently ephemeral. restrict
            // would throw an FK error both on that resave and on deleting
            // a prospect that has a contact linked to a location.
            $table->foreignId('prospect_location_id')->constrained('prospect_locations')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_contact_addresses');
    }
};
