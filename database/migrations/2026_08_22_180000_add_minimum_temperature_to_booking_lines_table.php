<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Minimum temperature (°C) for a Reefer Van cargo line - same field the
     * CRM lead's Booking Requirements stage collects for RF containers.
     */
    public function up(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->decimal('minimum_temperature', 5, 1)->nullable()->after('hazardous_document_path');
        });
    }

    public function down(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->dropColumn('minimum_temperature');
        });
    }
};
