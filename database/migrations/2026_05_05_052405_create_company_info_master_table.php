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
        Schema::create('company_info_master', function (Blueprint $table) {
            $table->id();

            $table->string('customer_code')->unique();
            $table->string('company_name');
            $table->text('registered_address')->nullable();

            $table->string('contact_number_1')->nullable();
            $table->string('contact_number_2')->nullable();

            $table->string('industry')->nullable(); // LOV
            $table->string('organization_type')->nullable(); // LOV

            $table->string('tax_identification_number')->nullable();
            $table->date('business_start_date')->nullable();

            $table->integer('number_of_employees')->nullable();
            $table->boolean('synkar')->default(false);

            $table->decimal('estimated_annual_revenue', 15, 2)->nullable();
            $table->decimal('estimated_annual_net_income', 15, 2)->nullable();

            $table->string('company_url')->nullable();

            // shipper / consignee tagging
            $table->enum('customer_type', ['shipper', 'consignee', 'both']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_info_master');
    }
};
