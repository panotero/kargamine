<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('container_assets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('container_variant_id')
                ->constrained('container_variants')
                ->restrictOnDelete();

            $table->string('container_no')->unique();

            $table->unsignedTinyInteger('status')->default(1);
            // 1 available, 2 booked, 3 in transit, 4 under repair, 5 damaged, 6 out of service

            $table->foreignId('current_port_id')
                ->nullable()
                ->constrained('ports', 'port_id')
                ->nullOnDelete();

            $table->string('current_pier_reference')->nullable();
            $table->timestamp('last_movement_at')->nullable();
            $table->text('condition_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('container_assets');
    }
};
