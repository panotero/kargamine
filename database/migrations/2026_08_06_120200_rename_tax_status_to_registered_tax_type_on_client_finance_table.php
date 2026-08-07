<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_finance', function (Blueprint $table) {
            $table->renameColumn('tax_status', 'registered_tax_type');
        });
    }

    public function down(): void
    {
        Schema::table('client_finance', function (Blueprint $table) {
            $table->renameColumn('registered_tax_type', 'tax_status');
        });
    }
};
