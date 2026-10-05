<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Service Type" moves from the delivery_type_id FK to a fixed 4-value
 * string (Door - Door / Door - Pier / Pier - Door / Pier - Pier), stored
 * directly on each row. delivery_type_id is left in place (deprecated, not
 * dropped - other display code still eager-loads deliveryType()).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            $table->string('service_type')->nullable()->after('delivery_type_id');
        });

        Schema::table('proposal_request_product_containers', function (Blueprint $table) {
            $table->string('service_type')->nullable()->after('delivery_type_id');
        });

        Schema::table('proposal_request_product_rolling_cargo', function (Blueprint $table) {
            $table->string('service_type')->nullable()->after('delivery_type_id');
        });

        Schema::table('proposal_request_product_loose_cargo', function (Blueprint $table) {
            $table->string('service_type')->nullable()->after('delivery_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });

        Schema::table('proposal_request_product_containers', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });

        Schema::table('proposal_request_product_rolling_cargo', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });

        Schema::table('proposal_request_product_loose_cargo', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }
};
