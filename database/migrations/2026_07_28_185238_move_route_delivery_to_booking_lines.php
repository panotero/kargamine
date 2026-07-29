<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Different cargo lines in the same booking can go to different
     * destinations / use a different Door-Pier mode - so route, lane,
     * tariff and delivery type all move from the booking header down to
     * each booking_line. Nullable: existing dev-seeded lines predate this
     * and have nothing to backfill from; the application layer requires
     * these on every new line going forward.
     *
     * SQLite (used by the test suite) can't drop a foreign key constraint
     * on its own - Laravel's grammar refuses even with doctrine/dbal
     * installed - so dropForeign() is skipped there; dropColumn() alone
     * still removes the column correctly (SQLite recreates the table
     * under the hood). MySQL still needs the explicit dropForeign() first.
     */
    protected function isSqlite(): bool
    {
        return Schema::getConnection()->getDriverName() === 'sqlite';
    }

    public function up(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->foreignId('origin_port_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('ports', 'port_id')
                ->restrictOnDelete();

            $table->foreignId('destination_port_id')
                ->nullable()
                ->after('origin_port_id')
                ->constrained('ports', 'port_id')
                ->restrictOnDelete();

            $table->foreignId('origin_area_id')
                ->nullable()
                ->after('destination_port_id')
                ->constrained('serviceable_areas', 'area_id')
                ->restrictOnDelete();

            $table->foreignId('destination_area_id')
                ->nullable()
                ->after('origin_area_id')
                ->constrained('serviceable_areas', 'area_id')
                ->restrictOnDelete();

            $table->foreignId('delivery_type_id')
                ->nullable()
                ->after('destination_area_id')
                ->constrained('delivery_types', 'delivery_type_id')
                ->restrictOnDelete();

            $table->foreignId('lane_id')
                ->nullable()
                ->after('delivery_type_id')
                ->constrained('lanes', 'lane_id')
                ->restrictOnDelete();

            $table->foreignId('tariff_rate_id')
                ->nullable()
                ->after('lane_id')
                ->constrained('lane_tariff_rates', 'rate_id')
                ->restrictOnDelete();

            $table->decimal('trucking_snapshot', 12, 2)->default(0)->after('line_total');
        });

        Schema::table('booking_port_charges', function (Blueprint $table) {
            $table->foreignId('booking_line_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('booking_lines')
                ->cascadeOnDelete();
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! $this->isSqlite()) {
                $table->dropForeign(['lane_id']);
                $table->dropForeign(['origin_area_id']);
                $table->dropForeign(['destination_area_id']);
                $table->dropForeign(['delivery_type_id']);
                $table->dropForeign(['tariff_rate_id']);
            }

            $table->dropColumn([
                'lane_id', 'origin_area_id', 'destination_area_id', 'delivery_type_id', 'tariff_rate_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('lane_id')->nullable()->constrained('lanes', 'lane_id')->restrictOnDelete();
            $table->foreignId('origin_area_id')->nullable()->constrained('serviceable_areas', 'area_id')->restrictOnDelete();
            $table->foreignId('destination_area_id')->nullable()->constrained('serviceable_areas', 'area_id')->restrictOnDelete();
            $table->foreignId('delivery_type_id')->nullable()->constrained('delivery_types', 'delivery_type_id')->restrictOnDelete();
            $table->foreignId('tariff_rate_id')->nullable()->constrained('lane_tariff_rates', 'rate_id')->restrictOnDelete();
        });

        Schema::table('booking_port_charges', function (Blueprint $table) {
            if (! $this->isSqlite()) {
                $table->dropForeign(['booking_line_id']);
            }

            $table->dropColumn('booking_line_id');
        });

        Schema::table('booking_lines', function (Blueprint $table) {
            if (! $this->isSqlite()) {
                $table->dropForeign(['origin_port_id']);
                $table->dropForeign(['destination_port_id']);
                $table->dropForeign(['origin_area_id']);
                $table->dropForeign(['destination_area_id']);
                $table->dropForeign(['delivery_type_id']);
                $table->dropForeign(['lane_id']);
                $table->dropForeign(['tariff_rate_id']);
            }

            $table->dropColumn([
                'origin_port_id', 'destination_port_id', 'origin_area_id', 'destination_area_id',
                'delivery_type_id', 'lane_id', 'tariff_rate_id', 'trucking_snapshot',
            ]);
        });
    }
};
