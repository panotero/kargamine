<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_of_values_table', function (Blueprint $table) {
            $table->id('lov_id');

            $table->unsignedBigInteger('lov_optionId');

            $table->string('lov_code');
            $table->string('lov_name');
            $table->text('lov_description')->nullable();

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('lov_optionId')
                ->references('option_id')
                ->on('options_table')
                ->onDelete('cascade');

            // Indexes for performance
            $table->index('lov_optionId');
            $table->index('lov_name');

            // Prevent duplicate values per option
            $table->unique(['lov_optionId', 'lov_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_of_values_table');
    }
};
