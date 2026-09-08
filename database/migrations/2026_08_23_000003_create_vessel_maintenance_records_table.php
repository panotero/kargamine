<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Service/maintenance/repair log for a vessel. next_due_at lets
     * Vessel Management surface an upcoming-maintenance warning; it's
     * optional since not every entry (e.g. an unscheduled repair) has one.
     */
    public function up(): void
    {
        Schema::create('vessel_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained('vessels')->cascadeOnDelete();
            $table->string('maintenance_type');
            $table->date('performed_at');
            $table->date('completed_at')->nullable();
            $table->text('description')->nullable();
            $table->string('performed_by')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->date('next_due_at')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['vessel_id', 'performed_at'], 'vessel_maint_rec_vessel_performed_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessel_maintenance_records');
    }
};
