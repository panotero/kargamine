<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The Products tab's Container and Trucking forms had no quantity concept.
 * Column is DB-nullable for safety on existing rows; the controller enforces
 * "required" on new writes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_request_product_containers', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->nullable()->after('container_type');
        });

        Schema::table('proposal_request_product_truckings', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->nullable()->after('trucking_cargo_type');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_request_product_containers', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });

        Schema::table('proposal_request_product_truckings', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
