<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_port_charges', function (Blueprint $table) {
            $table->id('booking_port_charge_id');

            $table->foreignId('booking_id')
                ->constrained('bookings', 'booking_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('port_id')
                ->constrained('ports', 'port_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('charge_type_id')
                ->constrained('charge_types', 'charge_type_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('role', ['ORIGIN', 'DESTINATION']);
            $table->decimal('amount_snapshot', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['booking_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_port_charges');
    }
};
