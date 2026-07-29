<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Step 2's "transaction details" fields (consignee, cargo type,
     * declared value, delivery dates) - a line without these is what the
     * SOP calls a "Tentative Booking" (just quantities lodged under the
     * booking reference); once every line on a booking has them filled
     * in, the booking counts as "Live" for the Cargo Build-Up dashboard.
     * Kept per-line (not on the booking header) to match the existing
     * per-line destination/delivery pattern - different cargo lines can
     * go to different consignees.
     */
    public function up(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->string('consignee_name')->nullable()->after('trucking_snapshot');
            $table->string('consignee_address')->nullable()->after('consignee_name');
            $table->string('consignee_contact_person')->nullable()->after('consignee_address');
            $table->string('consignee_contact_number')->nullable()->after('consignee_contact_person');

            $table->string('cargo_type')->nullable()->after('consignee_contact_number');
            $table->text('other_cargo_details')->nullable()->after('cargo_type');
            $table->decimal('declared_value', 14, 2)->nullable()->after('other_cargo_details');

            $table->date('delivery_date')->nullable()->after('declared_value');
            $table->string('delivery_date_notes')->nullable()->after('delivery_date');
            $table->date('first_delivery_date')->nullable()->after('delivery_date_notes');
            $table->date('last_delivery_date')->nullable()->after('first_delivery_date');
        });
    }

    public function down(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->dropColumn([
                'consignee_name', 'consignee_address', 'consignee_contact_person', 'consignee_contact_number',
                'cargo_type', 'other_cargo_details', 'declared_value',
                'delivery_date', 'delivery_date_notes', 'first_delivery_date', 'last_delivery_date',
            ]);
        });
    }
};
