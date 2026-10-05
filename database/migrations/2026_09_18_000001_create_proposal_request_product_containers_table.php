<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Products tab's Container form (CV/FR/RF unified) - deliberately a
 * parallel, richer structure to proposal_request_containers (Requirements
 * tab), not a reuse of it. minimum_temperature only applies to RF, same
 * type-driven-visibility convention as the Requirements tab's container
 * form.
 *
 * Every FK gets an explicit short constraint name - the table name alone
 * is already long enough that MySQL's 64-char identifier limit is hit by
 * the auto-generated names (same issue hit earlier this session with the
 * charter_cargo/charter_ports tables).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests', 'id', 'prpcon_request_fk')->cascadeOnDelete();

            $table->string('container_type'); // CV | FR | RF
            $table->foreignId('container_size_id')->nullable()->constrained('container_size', 'id', 'prpcon_size_fk')->nullOnDelete();
            $table->decimal('minimum_temperature', 6, 2)->nullable();

            $table->foreignId('delivery_type_id')->nullable()->constrained('delivery_types', 'delivery_type_id', 'prpcon_delivery_fk')->nullOnDelete();
            $table->foreignId('origin_prospect_location_id')->nullable()->constrained('prospect_locations', 'id', 'prpcon_origin_loc_fk')->nullOnDelete();
            $table->foreignId('destination_prospect_location_id')->nullable()->constrained('prospect_locations', 'id', 'prpcon_dest_loc_fk')->nullOnDelete();
            $table->foreignId('origin_port_id')->nullable()->constrained('ports', 'port_id', 'prpcon_origin_port_fk')->nullOnDelete();
            $table->foreignId('destination_port_id')->nullable()->constrained('ports', 'port_id', 'prpcon_dest_port_fk')->nullOnDelete();

            $table->string('cargo_type')->nullable();
            $table->text('cargo_description')->nullable();
            $table->string('terms_of_payment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_containers');
    }
};
