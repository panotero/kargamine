<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charge_types', function (Blueprint $table) {
            $table->id('charge_type_id');
            $table->string('code', 20)->unique(); // DOC_STAMP, SEC_FEE, EXCISE_TAX, WT_FEE, WHARFAGE
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charge_types');
    }
};
