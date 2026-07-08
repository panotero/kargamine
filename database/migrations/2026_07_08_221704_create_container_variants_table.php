<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('container_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('container_id')
                ->constrained('containers')
                ->cascadeOnDelete();

            $table->foreignId('container_class_id')
                ->constrained('container_class')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('container_size_id')
                ->constrained('container_size')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['container_id', 'container_class_id', 'container_size_id'],
                'container_variants_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('container_variants');
    }
};
