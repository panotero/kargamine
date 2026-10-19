<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Immutable, auditable snapshot of client_proposal_rates lines captured the
 * moment a signed document is attached (ClientProposalController::attachSigned
 * -> APPROVED to ACCEPTED). Proposal rate lines can still change after
 * Accepted (destroyRate has no status guard), and contract creation only
 * copies rates later at contract time - so this is the one durable record of
 * exactly what the signed PDF showed.
 *
 * Catalog id columns are deliberately PLAIN unsignedBigInteger, not
 * foreignId()->constrained(): the snapshot must survive a later edit/delete
 * of the referenced port/container/variant/area catalog row. container_class_id
 * and container_size_id stay nullable end-to-end (see CLAUDE.md) - null class
 * means "base price, no class", null size means Loose/Rolling cargo priced by
 * class instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_proposal_signed_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_proposal_id')->constrained('client_proposals')->cascadeOnDelete();

            // Traceability pointer only - no FK, so the snapshot stays
            // decoupled from the source row's lifecycle.
            $table->unsignedBigInteger('source_rate_id')->nullable();

            $table->unsignedBigInteger('origin_port_id');
            $table->unsignedBigInteger('origin_pickup_area_id')->nullable();
            $table->unsignedBigInteger('destination_port_id');
            $table->unsignedBigInteger('destination_pickup_area_id')->nullable();

            $table->unsignedBigInteger('container_id');
            $table->unsignedBigInteger('container_class_id')->nullable();
            $table->unsignedBigInteger('container_size_id')->nullable();
            $table->unsignedBigInteger('container_variant_id');

            $table->integer('min_van_qty')->nullable();
            $table->decimal('base_rate', 12, 2)->default(0);
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('final_rate', 12, 2)->default(0);

            // Denormalized from the parent ClientProposal at signing time so
            // the snapshot is fully self-describing.
            $table->boolean('include_special_charges')->default(false);
            $table->boolean('include_port_charges')->default(false);
            $table->boolean('include_handling_fee')->default(false);
            $table->boolean('include_general_charges')->default(false);

            // [{required_service, location, unit, quantity}, ...] captured
            // from client_proposal_rate_ancillary_services at signing time.
            $table->json('ancillary_services')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_proposal_signed_rates');
    }
};
