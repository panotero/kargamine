<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Step 3 (Issuance of ATW/CAN) - one document per cargo line, since
     * door/pier service type (and therefore ATW vs CAN) is a per-line
     * concept in this app, not a booking-header one. A line can only ever
     * have one dispatch document - re-issuing isn't modeled here.
     */
    public function up(): void
    {
        Schema::create('booking_dispatch_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_line_id')
                ->unique()
                ->constrained('booking_lines')
                ->cascadeOnDelete();

            $table->foreignId('booking_id')
                ->constrained('bookings', 'booking_id')
                ->cascadeOnDelete();

            $table->string('document_type'); // ATW | CAN
            $table->string('document_number')->unique();

            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('generated_at')->nullable();

            // ***ATW needs to consider Single Pickup and Advance Pull Out
            $table->boolean('is_single_pickup')->default(false);
            $table->boolean('is_advance_pull_out')->default(false);

            // Trip Type identification
            $table->string('trip_type')->nullable(); // Tandem | Tandem Foul | Single | Single Foul
            $table->string('trailer_capacity')->nullable();
            $table->unsignedInteger('convan_count')->nullable();
            $table->string('convan_size')->nullable();

            // Dispatcher assigns truck and truck personnel
            $table->string('authorized_trucker')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('authorized_driver')->nullable();
            $table->string('helper')->nullable();
            $table->string('coordinator_checker')->nullable();

            // Cargo CY Operations
            $table->timestamp('cy_empty_pull_out_at')->nullable();
            $table->timestamp('cy_stuffing_activity_at')->nullable();
            $table->timestamp('cy_stripping_activity_at')->nullable();
            $table->timestamp('cy_delivery_of_cargo_at')->nullable();
            $table->timestamp('estimated_departure_at')->nullable();
            $table->timestamp('estimated_arrival_at')->nullable();

            $table->timestamps();

            $table->index(['booking_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_dispatch_documents');
    }
};
