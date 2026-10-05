<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Repeatable port-call row within one charter booking - "PORT 1", "PORT 2"
 * etc in the UI, ordered by sort_order (insertion order). Port-level (not
 * location-level) per the brief, searchable-select just like other
 * port pickers in the app.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_charter_ports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_charter_id');
            $table->foreign('proposal_request_charter_id', 'prc_port_charter_fk')
                ->references('id')->on('proposal_request_charters')->cascadeOnDelete();
            $table->foreignId('port_id')->nullable();
            $table->foreign('port_id', 'prc_port_port_fk')
                ->references('port_id')->on('ports')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_charter_ports');
    }
};
