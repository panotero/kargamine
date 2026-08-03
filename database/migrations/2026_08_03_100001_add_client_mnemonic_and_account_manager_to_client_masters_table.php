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
        Schema::table('client_masters', function (Blueprint $table) {
            $table->string('client_mnemonic')->nullable()->unique()->after('company_name');
            $table->foreignId('account_manager_id')->nullable()->after('sales_rep_id')
                ->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->dropConstrainedForeignId('account_manager_id');
            $table->dropColumn('client_mnemonic');
        });
    }
};
