<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_company_info', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_id')
                ->constrained('crm_leads')
                ->cascadeOnDelete();

            $table->string('company_name');
            $table->string('company_address')->nullable();
            $table->string('authorized_signatory_name')->nullable();
            $table->string('authorized_signatory_position')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_info');
    }
};
