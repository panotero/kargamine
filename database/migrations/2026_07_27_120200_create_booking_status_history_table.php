<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_status_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings', 'booking_id')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('from_status')->nullable(); // null on the first (Draft) row
            $table->unsignedTinyInteger('to_status');

            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('note')->nullable(); // e.g. the cancellation reason
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->index(['booking_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_status_history');
    }
};
