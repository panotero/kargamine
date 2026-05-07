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
        Schema::create('stages_info', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('company_info_master')
                ->cascadeOnDelete();

            $table->string('stage')->nullable(); // LOV

            $table->date('proposal_requested_date')->nullable();
            $table->date('proposal_submitted_date')->nullable();
            $table->date('negotiation_date')->nullable();
            $table->date('won_awarded_date')->nullable();
            $table->date('lost_closed_date')->nullable();

            $table->decimal('monthly_sales_forecast', 15, 2)->nullable();
            $table->date('forecast_transaction_month')->nullable();

            $table->integer('potential_volume_month')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages_info');
    }
};
