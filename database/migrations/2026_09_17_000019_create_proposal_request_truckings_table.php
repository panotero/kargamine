<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Second "product" alongside container booking requirements - a trucking
 * pickup/delivery requirement estimate (client needs a truck to move cargo
 * from one point to another). Its own table (not a container_type variant)
 * since its field set is unrelated to the container form.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_truckings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests')->cascadeOnDelete();

            // Matches the selected "Trucking Cargo Type" LOV's lov_name,
            // same free-text-matching-an-LOV convention used by
            // prospect_locations.address_type elsewhere in this app.
            $table->string('trucking_cargo_type')->nullable();

            $table->integer('quantity')->nullable();
            $table->string('frequency')->nullable();
            $table->string('dispatch_mode')->nullable(); // 'single' | 'tandem'

            $table->foreignId('origin_location_id')->nullable()->constrained('locations', 'location_id')->nullOnDelete();
            $table->foreignId('destination_location_id')->nullable()->constrained('locations', 'location_id')->nullOnDelete();

            $table->decimal('declared_value_per_unit', 15, 2)->nullable();
            $table->decimal('weight', 12, 2)->nullable();
            $table->string('weight_unit', 10)->nullable();

            $table->string('cargo_type')->nullable();
            $table->text('general_cargo_description')->nullable();
            $table->text('special_requirements')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_truckings');
    }
};
