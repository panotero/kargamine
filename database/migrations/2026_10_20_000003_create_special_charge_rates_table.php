<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_charge_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('special_charge_id');
            $table->string('unit');
            $table->decimal('base_value', 12, 2);
            $table->timestamps();

            $table->foreign('special_charge_id')
                ->references('special_charge_id')->on('special_charges')
                ->cascadeOnDelete();

            $table->unique(['special_charge_id', 'unit']);
        });

        // Backfill: every existing special_charges row with a non-null
        // base_value gets one special_charge_rates row under unit "others"
        // so already-configured charges keep pricing after this change.
        $now = now();

        $existingCharges = DB::table('special_charges')->whereNotNull('base_value')->get();

        foreach ($existingCharges as $charge) {
            DB::table('special_charge_rates')->insert([
                'special_charge_id' => $charge->special_charge_id,
                'unit' => 'others',
                'base_value' => $charge->base_value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('special_charge_rates');
    }
};
