<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance', function (Blueprint $table) {
            $table->id(); // primary key

            $table->enum('transaction', ['ORS', 'DV']); // ORS or DV
            $table->date('date_processed')->nullable(); // required
            $table->string('payee', 255); // required
            $table->text('particular'); // required
            $table->string('responsibility_center', 255);
            $table->string('expenditure', 255)->nullable();
            $table->string('uacs_object_code', 50)->nullable();
            $table->decimal('amount', 15, 2); // total digits 15, 2 decimals
            $table->string('fund_cluster', 50)->nullable();
            $table->date('date_signed')->nullable();
            $table->string('file_path', 255); // path to uploaded file

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance');
    }
};
