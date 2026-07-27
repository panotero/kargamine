<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('booking_id');
            $table->string('code')->nullable()->unique()->after('uuid');

            $table->foreignId('client_id')
                ->nullable()
                ->after('code')
                ->constrained('client_masters')
                ->restrictOnDelete();

            $table->foreignId('client_contract_id')
                ->nullable()
                ->after('client_id')
                ->constrained('client_contracts')
                ->nullOnDelete();

            $table->unsignedTinyInteger('status')->default(1)->after('client_contract_id');
            // 1 draft, 2 confirmed, 3 in transit, 4 delivered, 5 completed, 6 cancelled
        });

        // Pricing detail moves to booking_lines (per cargo line) once multi-line
        // pricing lands - bsc/ra/gri/art have no data source anymore (dropped
        // from lane_tariff_rates with no replacement) and aren't carried forward.
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'frt_snapshot',
                'bsc_snapshot',
                'ra_snapshot',
                'gri_snapshot',
                'discount_type_snapshot',
                'discount_value_snapshot',
                'frt_after_discount_snapshot',
                'art_snapshot',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('frt_snapshot', 12, 2)->default(0);
            $table->decimal('bsc_snapshot', 12, 2)->default(0);
            $table->decimal('ra_snapshot', 12, 2)->default(0);
            $table->decimal('gri_snapshot', 12, 2)->default(0);
            $table->string('discount_type_snapshot')->nullable();
            $table->decimal('discount_value_snapshot', 12, 2)->default(0);
            $table->decimal('frt_after_discount_snapshot', 12, 2)->default(0);
            $table->decimal('art_snapshot', 12, 2)->default(0);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_contract_id');
            $table->dropConstrainedForeignId('client_id');
            $table->dropColumn(['uuid', 'code', 'status']);
        });
    }
};
