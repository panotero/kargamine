<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Products tab's Trucking form - deliberately a smaller field set than the
 * other products (no quantity/declared value/weight - not requested).
 * Explicit short FK constraint names throughout - see the containers
 * migration's comment.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_truckings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests', 'id', 'prptrk_request_fk')->cascadeOnDelete();

            $table->string('trucking_cargo_type')->nullable();
            $table->string('dispatch_mode')->nullable(); // single | tandem

            $table->foreignId('origin_prospect_location_id')->nullable()->constrained('prospect_locations', 'id', 'prptrk_origin_loc_fk')->nullOnDelete();
            $table->foreignId('destination_prospect_location_id')->nullable()->constrained('prospect_locations', 'id', 'prptrk_dest_loc_fk')->nullOnDelete();

            $table->string('cargo_type')->nullable();
            $table->text('cargo_description')->nullable();
            $table->string('terms_of_payment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_truckings');
    }
};
