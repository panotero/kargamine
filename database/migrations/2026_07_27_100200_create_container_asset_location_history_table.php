<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('container_asset_location_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('container_asset_id')
                ->constrained('container_assets')
                ->cascadeOnDelete();

            $table->foreignId('port_id')
                ->constrained('ports', 'port_id')
                ->restrictOnDelete();

            $table->string('pier_reference')->nullable();
            $table->string('status_at_time');
            $table->string('source'); // manual_relocation | pier_checkin | booking_assignment | booking_release

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['container_asset_id', 'recorded_at'], 'cont_asset_loc_hist_asset_recorded_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('container_asset_location_history');
    }
};
