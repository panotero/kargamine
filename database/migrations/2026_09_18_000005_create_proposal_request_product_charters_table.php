<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Products tab's Charter form - a wholly parallel, much richer structure
 * than the Requirements tab's simple proposal_request_charters. Cargo Info
 * and Ports are added afterward via their own sub-modals (see
 * proposal_request_product_charter_cargo/_ports), not built inline before
 * this row is saved. cargo_manifest_path is prepared for a future upload
 * endpoint - no <input type=file> wiring yet, same convention as
 * ClientContract.signed_document_path.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_request_product_charters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_request_id')->constrained('proposal_requests', 'id', 'prpchr_request_fk')->cascadeOnDelete();

            $table->string('vessel_name')->nullable();
            $table->decimal('vessel_dead_weight', 12, 2)->nullable();
            $table->date('charter_start_date')->nullable();
            $table->date('charter_end_date')->nullable();
            $table->date('loading_date')->nullable();
            $table->integer('laytime_loading_days')->nullable();
            $table->integer('laytime_unloading_days')->nullable();
            $table->boolean('demurrage_charges')->default(false);
            $table->boolean('lashing_service')->default(false);
            $table->boolean('insurance_services')->default(false);
            $table->text('other_charges')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('cargo_manifest_path')->nullable();
            $table->decimal('declared_value', 15, 2)->nullable();
            $table->decimal('weight', 12, 2)->nullable();
            $table->string('weight_unit', 10)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_request_product_charters');
    }
};
