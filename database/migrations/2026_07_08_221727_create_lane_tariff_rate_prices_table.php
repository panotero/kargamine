<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lane_tariff_rate_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lane_tariff_rate_id')
                ->constrained('lane_tariff_rates', 'rate_id')
                ->cascadeOnDelete();

            $table->foreignId('container_variant_id')
                ->constrained('container_variants')
                ->restrictOnDelete();

            // FRT specific to this container class/size combination on this
            // lane tariff version - the "different price per combination"
            // requirement lives here, not on lane_tariff_rates itself.
            $table->decimal('frt', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(
                ['lane_tariff_rate_id', 'container_variant_id'],
                'lane_tariff_rate_prices_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lane_tariff_rate_prices');
    }
};
