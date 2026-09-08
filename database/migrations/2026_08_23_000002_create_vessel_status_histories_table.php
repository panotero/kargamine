<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Snapshot-per-row (not a diff) of every status transition a vessel
     * goes through, mirroring container_asset_location_histories. Kept
     * separate from vessel_maintenance_records because a status change and
     * a service/repair event are semantically different (a status change
     * can happen with no maintenance involved, e.g. tagging a vessel
     * Out of Service for a crewing issue).
     */
    public function up(): void
    {
        Schema::create('vessel_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained('vessels')->cascadeOnDelete();
            $table->unsignedTinyInteger('from_status')->nullable();
            $table->unsignedTinyInteger('to_status');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['vessel_id', 'recorded_at'], 'vessel_status_hist_vessel_recorded_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessel_status_histories');
    }
};
