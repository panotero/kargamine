<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Polymorphic - attaches to a Container, Rolling Cargo, Loose Cargo, or
 * Trucking product row (never Charter, which gets its own Cargo Info/Ports
 * sub-lists instead). ancillaryable_type stores the short morph-map alias
 * (container|rolling_cargo|loose_cargo|trucking), not a raw class name -
 * see AppServiceProvider::boot().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_ancillary_services', function (Blueprint $table) {
            $table->id();
            $table->morphs('ancillaryable', 'prpanc_ancillaryable_idx');

            $table->string('ancillary_type')->nullable();
            $table->foreignId('cargo_yard_id')->nullable()->constrained('cargo_yards', 'cargo_yard_id', 'prpanc_yard_fk')->nullOnDelete();
            $table->string('ancillary_unit')->nullable();
            $table->text('ancillary_remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_ancillary_services');
    }
};
