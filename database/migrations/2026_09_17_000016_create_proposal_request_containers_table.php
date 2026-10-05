<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Same container column set as prospect_containers_legacy (see
 * 2026_07_14_021949_create_crm_lead_containers_table.php,
 * 2026_07_14_030206_add_lookup_columns_to_crm_lead_containers_table.php,
 * 2026_07_29_003752_rename_required_temperature_on_crm_lead_containers_table.php
 * and 2026_09_01_000001_add_cargo_type_to_crm_lead_containers_table.php),
 * minus the legacy free-text origin/destination/convan_class/convan_size
 * columns that were superseded by the *_port_id / container_*_id FKs.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();

            $table->string('container_type', 5);

            $table->foreignId('origin_port_id')->nullable()->constrained('ports', 'port_id')->nullOnDelete();
            $table->foreignId('destination_port_id')->nullable()->constrained('ports', 'port_id')->nullOnDelete();
            $table->foreignId('container_class_id')->nullable()->constrained('container_class')->nullOnDelete();
            $table->foreignId('container_size_id')->nullable()->constrained('container_size')->nullOnDelete();

            $table->integer('quantity')->nullable();
            $table->decimal('estimated_cbm', 12, 2)->nullable();
            $table->decimal('estimated_ton', 12, 2)->nullable();
            $table->decimal('minimum_temperature', 5, 2)->nullable();

            $table->string('service_mode')->nullable();
            $table->string('service_mode_origin')->nullable();
            $table->string('service_mode_destination')->nullable();

            $table->string('cargo_type')->nullable();
            $table->text('general_cargo_description')->nullable();
            $table->boolean('dangerous_cargo')->default(false);
            $table->text('dg_documentary_requirement')->nullable();
            $table->text('special_requirements')->nullable();
            $table->text('special_notes')->nullable();

            $table->decimal('declared_value_per_unit', 15, 2)->nullable();
            $table->string('frequency')->nullable();
            $table->string('booking_unit_type')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_containers');
    }
};
