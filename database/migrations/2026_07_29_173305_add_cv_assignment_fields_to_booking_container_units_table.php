<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Step 4 (Convan/Flat Rack Assignment) - seal_no already existed on
     * this table; proforma_bl_number and waybill_number are the other two
     * per-container identifiers the SOP lists (ConVan No. is already the
     * linked container_asset's container_no, nothing new needed for that).
     */
    public function up(): void
    {
        Schema::table('booking_container_units', function (Blueprint $table) {
            $table->string('proforma_bl_number')->nullable()->after('seal_no');
            $table->string('waybill_number')->nullable()->after('proforma_bl_number');
        });
    }

    public function down(): void
    {
        Schema::table('booking_container_units', function (Blueprint $table) {
            $table->dropColumn(['proforma_bl_number', 'waybill_number']);
        });
    }
};
