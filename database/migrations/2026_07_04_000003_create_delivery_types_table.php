<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_types', function (Blueprint $table) {
            $table->id('delivery_type_id');
            $table->string('code', 5)->unique(); // DD, PD, PP, DP
            $table->string('name', 50); // Door-to-Door, Pier-to-Door, ...
            $table->boolean('includes_origin_trucking')->default(false);
            $table->boolean('includes_destination_trucking')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_types');
    }
};
