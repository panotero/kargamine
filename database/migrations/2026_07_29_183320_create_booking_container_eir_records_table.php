<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Steps 5 & 9 (EIR Out/In) - one row per direction per container
     * unit, so a unit gets at most one OUT record and one IN record
     * across its single round trip (enforced in the app layer, not a DB
     * constraint, since a unique index on (unit, direction) would also
     * need to survive if a unit is ever reused - not modeled yet).
     *
     * driver_id_photo_path/shipper_representative_name belong to the OUT
     * record - the SOP ties the driver ID photo requirement to Gate Pass
     * Out (Step 6), but it shares the same upload mechanism as EIR's
     * damage photos, so it rides along here instead of duplicating file
     * handling in two places. convan_class_id is IN-only (SOP: "Update
     * EIR: Convan Class").
     */
    public function up(): void
    {
        Schema::create('booking_container_eir_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_container_unit_id')
                ->constrained('booking_container_units')
                ->cascadeOnDelete();

            $table->string('direction'); // OUT | IN

            $table->string('damage_codes')->nullable();
            $table->text('damage_remarks')->nullable();
            $table->string('convan_checklist_path')->nullable();
            $table->json('damage_photo_paths')->nullable();

            // IN only
            $table->foreignId('convan_class_id')->nullable()->constrained('container_class')->nullOnDelete();

            // OUT only
            $table->string('shipper_representative_name')->nullable();
            $table->string('driver_id_photo_path')->nullable();

            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();

            $table->timestamps();

            $table->index(['booking_container_unit_id', 'direction'], 'booking_container_eir_unit_direction_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_container_eir_records');
    }
};
