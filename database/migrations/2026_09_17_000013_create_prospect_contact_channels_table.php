<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospect_contact_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_contact_id')->constrained('prospect_contacts')->cascadeOnDelete();

            $table->enum('channel_type', ['mobile', 'landline', 'email']);
            $table->string('value');
            $table->enum('contact_type', ['personal', 'business']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_contact_channels');
    }
};
