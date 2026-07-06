<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trucking_tariffs', function (Blueprint $table) {
            $table->id('trucking_tariff_id');
            $table->foreignId('area_id')
                ->constrained('serviceable_areas', 'area_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('delivery_type_id')
                ->constrained('delivery_types', 'delivery_type_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['area_id', 'delivery_type_id', 'effective_date'], 'trucking_tariffs_unique');
            $table->index(['area_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trucking_tariffs');
    }
};
