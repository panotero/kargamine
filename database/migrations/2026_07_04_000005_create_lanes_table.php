<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lanes', function (Blueprint $table) {
            $table->id('lane_id');
            $table->foreignId('origin_port_id')
                ->constrained('ports', 'port_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('destination_port_id')
                ->constrained('ports', 'port_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['origin_port_id', 'destination_port_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lanes');
    }
};
