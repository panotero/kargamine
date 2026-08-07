<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_ancillary_services', function (Blueprint $table) {
            $table->dropColumn([
                'client_code',
                'client_mnemonic',
                'client_business_name',
                'unit_rate_vat_ex',
                'mode_of_payment',
                'remarks',
            ]);
        });

        // Separate from the drop above and without ->after() - SQLite
        // (used by the test suite) doesn't support column positioning and
        // mixing add+drop in one Schema::table call was silently dropping
        // this column on that driver.
        Schema::table('client_ancillary_services', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('client_ancillary_services', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->string('client_code')->nullable();
            $table->string('client_mnemonic')->nullable();
            $table->string('client_business_name')->nullable();
            $table->decimal('unit_rate_vat_ex', 15, 2)->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->text('remarks')->nullable();
        });
    }
};
