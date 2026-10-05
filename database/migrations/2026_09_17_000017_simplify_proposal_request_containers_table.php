<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Simplifies the Prospect Requirements booking-requirement row: ports and
 * the split/single service-mode fields are replaced by a single
 * delivery_type_id (Door-Door/Door-Pier/etc - the same app-wide Delivery
 * Type settings the Booking module already uses), dangerous cargo / the DG
 * file field / special notes are dropped, and a weight + unit pair is added
 * after declared value.
 *
 * dropForeign() is skipped on SQLite (used by the test suite) - SQLite
 * refuses it unconditionally ("SQLite doesn't support dropping foreign
 * keys"), and dropColumn() alone is sufficient there since SQLite's
 * foreign key pragma isn't tied to a named constraint the way MySQL's is.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['origin_port_id']);
                $table->dropForeign(['destination_port_id']);
            }
            $table->dropColumn([
                'origin_port_id',
                'destination_port_id',
                'service_mode',
                'service_mode_origin',
                'service_mode_destination',
                'dangerous_cargo',
                'dg_documentary_requirement',
                'special_notes',
            ]);

            $table->foreignId('delivery_type_id')->nullable()->after('container_size_id')
                ->constrained('delivery_types', 'delivery_type_id')->nullOnDelete();
            $table->decimal('weight', 12, 2)->nullable()->after('declared_value_per_unit');
            $table->string('weight_unit', 10)->nullable()->after('weight');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_request_containers', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['delivery_type_id']);
            }
            $table->dropColumn(['delivery_type_id', 'weight', 'weight_unit']);

            $table->foreignId('origin_port_id')->nullable()->constrained('ports', 'port_id')->nullOnDelete();
            $table->foreignId('destination_port_id')->nullable()->constrained('ports', 'port_id')->nullOnDelete();
            $table->string('service_mode')->nullable();
            $table->string('service_mode_origin')->nullable();
            $table->string('service_mode_destination')->nullable();
            $table->boolean('dangerous_cargo')->default(false);
            $table->text('dg_documentary_requirement')->nullable();
            $table->text('special_notes')->nullable();
        });
    }
};
