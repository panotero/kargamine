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
        Schema::dropIfExists('client_billing');
    }

    /**
     * Reverse the migrations. Recreated minimally (not the full original
     * shape) so this migration is reversible without resurrecting fields
     * that were already redundant with the new client_finance columns.
     */
    public function down(): void
    {
        Schema::create('client_billing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->unique()->constrained('client_masters')->cascadeOnDelete();
            $table->string('billed_to')->nullable();
            $table->string('company_name')->nullable();
            $table->text('address')->nullable();
            $table->string('tin')->nullable();
            $table->timestamps();
        });
    }
};
