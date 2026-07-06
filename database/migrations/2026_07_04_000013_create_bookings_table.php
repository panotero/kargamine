<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id');

            $table->foreignId('lane_id')
                ->constrained('lanes', 'lane_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('origin_area_id')
                ->constrained('serviceable_areas', 'area_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('destination_area_id')
                ->constrained('serviceable_areas', 'area_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('delivery_type_id')
                ->constrained('delivery_types', 'delivery_type_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('tariff_rate_id')
                ->constrained('lane_tariff_rates', 'rate_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('vat_rate_id')
                ->constrained('vat_rates', 'vat_rate_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Optional - only set if this booking is billed under a signed
            // client contract instead of the standard walk-in tariff.
            $table->foreignId('contract_id')
                ->nullable()
                ->constrained('contracts')
                ->restrictOnDelete();

            $table->foreignId('contract_rate_id')
                ->nullable()
                ->constrained('contract_rates')
                ->restrictOnDelete();

            // Snapshot values captured at booking time - protects historical
            // accuracy even if the rate tables above change later.
            $table->decimal('frt_snapshot', 12, 2)->default(0); // FRT before any contract discount
            $table->decimal('bsc_snapshot', 12, 2)->default(0);
            $table->decimal('ra_snapshot', 12, 2)->default(0);
            $table->decimal('gri_snapshot', 12, 2)->default(0);

            // Contract discount snapshot - null/zero when no contract applied
            $table->string('discount_type_snapshot')->nullable(); // PERCENTAGE | FIXED
            $table->decimal('discount_value_snapshot', 12, 2)->default(0);
            $table->decimal('frt_after_discount_snapshot', 12, 2)->default(0); // frt_snapshot minus the discount

            $table->decimal('art_snapshot', 12, 2)->default(0); // frt_after_discount + bsc + total_adjustment
            $table->decimal('trucking_snapshot', 12, 2)->default(0);
            $table->decimal('vat_amount_snapshot', 12, 2)->default(0);
            $table->decimal('grand_total_snapshot', 12, 2)->default(0);

            $table->date('booking_date');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['lane_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
