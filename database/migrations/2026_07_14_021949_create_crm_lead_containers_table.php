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
        Schema::create('crm_lead_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('crm_leads')->cascadeOnDelete();

            // CV, FR, RF, LC, RC
            $table->string('container_type', 5);

            // Common to all types
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('booking_unit_type')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('declared_value_per_unit', 15, 2)->nullable();
            $table->string('frequency')->nullable();
            $table->text('general_cargo_description')->nullable();

            // CV / RF only
            $table->string('convan_class')->nullable();
            $table->string('convan_size')->nullable(); // CV only

            // RF only
            $table->decimal('required_temperature', 5, 2)->nullable();

            // LC / RC only
            $table->decimal('estimated_cbm', 12, 2)->nullable();
            $table->decimal('estimated_ton', 12, 2)->nullable();

            // Service mode - CV/FR/RF split by pier origin/destination;
            // LC/RC use the single `service_mode` field instead.
            $table->string('service_mode_origin')->nullable();
            $table->string('service_mode_destination')->nullable();
            $table->string('service_mode')->nullable();

            // Common to all types
            $table->boolean('dangerous_cargo')->default(false);
            $table->text('dg_documentary_requirement')->nullable();
            $table->text('special_requirements')->nullable();
            $table->text('special_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_lead_containers');
    }
};
