<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Steps 6-7 (Gate Pass Out/In) - scan-confirmation only for this
     * pass (EIR's damage/checklist/photo paperwork is a later phase). Gate
     * Pass Out gets a real document number per the SOP; Gate Pass In is
     * just an actual-timestamp update, no new number issued for it.
     */
    public function up(): void
    {
        Schema::table('booking_container_units', function (Blueprint $table) {
            $table->string('gate_pass_out_number')->nullable()->unique()->after('waybill_number');
            $table->timestamp('actual_gate_out_at')->nullable()->after('gate_pass_out_number');
            $table->foreignId('gate_out_scanned_by')->nullable()->after('actual_gate_out_at')
                ->constrained('users')->nullOnDelete();

            $table->timestamp('actual_gate_in_at')->nullable()->after('gate_out_scanned_by');
            $table->foreignId('gate_in_scanned_by')->nullable()->after('actual_gate_in_at')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('booking_container_units', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['gate_out_scanned_by']);
                $table->dropForeign(['gate_in_scanned_by']);
            }

            $table->dropColumn([
                'gate_pass_out_number', 'actual_gate_out_at', 'gate_out_scanned_by',
                'actual_gate_in_at', 'gate_in_scanned_by',
            ]);
        });
    }
};
