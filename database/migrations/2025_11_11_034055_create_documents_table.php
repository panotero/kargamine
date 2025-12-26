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
        Schema::create('documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->integer('document_control_number');
            $table->date('date_received');
            $table->text('particular');
            $table->string('office_origin', 100);
            $table->unsignedBigInteger('user_id');
            $table->string('document_form', 50);
            $table->string('document_type', 50);
            $table->date('date_of_document');
            $table->string('signatory', 100);
            $table->date('date_forwarded')->nullable();
            $table->string('destination_office', 100)->nullable();
            $table->string('involved_office', 255)->nullable(); // JSON array of office names
            $table->text('action_taken')->nullable();
            $table->string('status', 50)->default('Pending');
            $table->string('confidentiality', 50)->default('None');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
