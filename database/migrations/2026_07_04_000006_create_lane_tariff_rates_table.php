<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lane_tariff_rates', function (Blueprint $table) {
            $table->id('rate_id');
            $table->foreignId('lane_id')
                ->constrained('lanes', 'lane_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('frt', 12, 2)->default(0); // Freight charges
            $table->decimal('bsc', 12, 2)->default(0); // Bunker surcharge
            $table->decimal('ra', 12, 2)->default(0);  // Rate increase
            $table->decimal('gri', 12, 2)->default(0); // General rate increase
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['lane_id', 'effective_date']);
            $table->index(['lane_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lane_tariff_rates');
    }
};
