<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code')->unique(); // e.g. contract/agreement number

            $table->foreignId('proposal_id')
                ->constrained('proposals')
                ->restrictOnDelete(); // agreed proposal this contract was generated from

            $table->foreignId('lead_id')
                ->constrained('crm_leads')
                ->restrictOnDelete(); // client the contract is signed with

            $table->date('signed_date')->nullable();
            $table->date('valid_from');
            $table->date('valid_to');

            // 1 = draft, 2 = active, 3 = expired, 4 = terminated (mirrors proposals.status style)
            $table->integer('status')->default(1);

            $table->string('signed_document_path')->nullable(); // uploaded signed copy

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['lead_id', 'status']);
            $table->index(['valid_from', 'valid_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
