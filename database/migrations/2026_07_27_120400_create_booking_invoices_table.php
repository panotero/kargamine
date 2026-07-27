<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings', 'booking_id')
                ->cascadeOnDelete();

            $table->foreignId('client_id')->constrained('client_masters')->restrictOnDelete();

            $table->string('invoice_number')->unique();
            $table->unsignedTinyInteger('status')->default(1); // 1 draft, 2 sent, 3 paid, 4 void
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('due_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_invoices');
    }
};
