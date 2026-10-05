<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Drops ConVan Class / CBM / Ton (no longer part of any container-type
 * field list) and adds Origin/Destination Location (plain location, not
 * port - see report) plus Revenue Ton (RT) + Cargo Measurement (Rolling
 * Cargo / Loose Cargo only). dropForeign() guarded for SQLite same as
 * migration 000017.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['container_class_id']);
            }
            $table->dropColumn(['container_class_id', 'estimated_cbm', 'estimated_ton']);

            $table->foreignId('origin_location_id')->nullable()->after('delivery_type_id')
                ->constrained('locations', 'location_id')->nullOnDelete();
            $table->foreignId('destination_location_id')->nullable()->after('origin_location_id')
                ->constrained('locations', 'location_id')->nullOnDelete();
            $table->decimal('revenue_ton', 12, 2)->nullable()->after('minimum_temperature');
            $table->string('cargo_measurement')->nullable()->after('revenue_ton');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['origin_location_id']);
                $table->dropForeign(['destination_location_id']);
            }
            $table->dropColumn(['origin_location_id', 'destination_location_id', 'revenue_ton', 'cargo_measurement']);

            $table->foreignId('container_class_id')->nullable()->constrained('container_class')->nullOnDelete();
            $table->decimal('estimated_cbm', 12, 2)->nullable();
            $table->decimal('estimated_ton', 12, 2)->nullable();
        });
    }
};
