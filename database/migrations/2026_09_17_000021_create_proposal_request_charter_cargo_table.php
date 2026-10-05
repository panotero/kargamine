<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Repeatable cargo row within one charter booking (see
 * 2026_09_17_000020_create_proposal_request_charters_table.php).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_charter_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_charter_id');
            $table->foreign('proposal_request_charter_id', 'prc_cargo_charter_fk')
                ->references('id')->on('proposal_request_charters')->cascadeOnDelete();

            $table->string('cargo_type')->nullable();
            $table->text('general_cargo_description')->nullable();
            $table->text('special_requirements')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_charter_cargo');
    }
};
