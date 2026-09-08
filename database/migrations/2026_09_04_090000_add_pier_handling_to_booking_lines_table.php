<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Only meaningful when the corresponding side's mode is Pier (the mode
     * itself is never persisted directly - it's derived from delivery_type_id,
     * see BookingController::buildResolverInputFromBooking()). Origin only
     * ever uses stuffing/van_out; destination only ever uses stripping/van_out -
     * enforced by BookingController::validatePayload(), not by the schema.
     */
    public function up(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->string('origin_pier_handling', 20)->nullable()->after('delivery_type_id');
            $table->string('destination_pier_handling', 20)->nullable()->after('origin_pier_handling');
        });
    }

    public function down(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->dropColumn(['origin_pier_handling', 'destination_pier_handling']);
        });
    }
};
