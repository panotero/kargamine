<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serviceable_areas', function (Blueprint $table) {
            $table->id('area_id');
            $table->foreignId('port_id')
                ->constrained('ports', 'port_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('area_name', 150); // e.g. CABUYAO LAGUNA
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['port_id', 'area_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serviceable_areas');
    }
};
