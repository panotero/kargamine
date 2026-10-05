<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Products tab's Loose Cargo form - identical shape to Rolling Cargo minus
 * the Top Load Cargo sub-list (that's Rolling Cargo only). Explicit short
 * FK constraint names throughout - see the containers migration's comment.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_loose_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests', 'id', 'prploo_request_fk')->cascadeOnDelete();

            $table->string('cargo_type')->nullable();
            $table->text('cargo_details')->nullable();
            $table->integer('cargo_quantity')->nullable();
            $table->string('cargo_units')->nullable();
            $table->decimal('revenue_ton', 12, 2)->nullable();
            $table->string('revenue_ton_unit')->nullable(); // CBM | MT
            $table->string('cargo_measurement')->nullable();

            $table->foreignId('delivery_type_id')->nullable()->constrained('delivery_types', 'delivery_type_id', 'prploo_delivery_fk')->nullOnDelete();
            $table->foreignId('origin_prospect_location_id')->nullable()->constrained('prospect_locations', 'id', 'prploo_origin_loc_fk')->nullOnDelete();
            $table->foreignId('destination_prospect_location_id')->nullable()->constrained('prospect_locations', 'id', 'prploo_dest_loc_fk')->nullOnDelete();
            $table->foreignId('origin_port_id')->nullable()->constrained('ports', 'port_id', 'prploo_origin_port_fk')->nullOnDelete();
            $table->foreignId('destination_port_id')->nullable()->constrained('ports', 'port_id', 'prploo_dest_port_fk')->nullOnDelete();

            $table->string('terms_of_payment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_loose_cargo');
    }
};
