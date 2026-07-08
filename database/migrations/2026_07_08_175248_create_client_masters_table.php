<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_masters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Stage 1 - Company Information
            $table->string('customer_code')->nullable()->unique();
            $table->string('company_name')->nullable();
            $table->text('registered_address')->nullable();
            $table->string('contact_number_1')->nullable();
            $table->string('contact_number_2')->nullable();
            $table->string('industry')->nullable();      // LOV
            $table->string('organization_type')->nullable(); // LOV
            $table->string('tin')->nullable();
            $table->date('business_start_date')->nullable();
            $table->decimal('estimated_annual_revenue', 15, 2)->nullable();
            $table->string('company_url')->nullable();

            $table->foreignId('sales_rep_id')->nullable()
                ->constrained('users')->nullOnDelete();

            // Progress tracking
            $table->unsignedTinyInteger('current_stage')->default(1); // 1,2,3
            $table->boolean('is_complete')->default(false);

            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_masters');
    }
};
