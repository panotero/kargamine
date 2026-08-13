<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Loose Cargo / Rolling Cargo container types have no fixed size (they
     * vary and are priced by class - MT/CBM - instead), so
     * container_variants.container_size_id needs to allow null the same
     * way container_class_id already does.
     */
    public function up(): void
    {
        Schema::table('container_variants', function (Blueprint $table) {
            $table->foreignId('container_size_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('container_variants', function (Blueprint $table) {
            $table->foreignId('container_size_id')->nullable(false)->change();
        });
    }
};
