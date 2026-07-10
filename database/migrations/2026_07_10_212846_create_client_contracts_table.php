<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_contracts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code')->unique();
            $table->foreignId('client_id')->constrained('client_masters')->cascadeOnDelete();
            $table->foreignId('client_proposal_id')->nullable()->constrained('client_proposals')->nullOnDelete();
            $table->date('signed_date')->nullable();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->unsignedTinyInteger('status')->default(2); // 1 draft,2 active,3 expired,4 terminated
            $table->string('signed_document_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_contracts');
    }
};
