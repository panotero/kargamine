<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SOP Step 3's "Trigo" special case, generalized: some client accounts
     * always get an ATW regardless of the line's door/pier service type.
     * A boolean override per client keeps this from being a hardcoded
     * company-name check that breaks the moment the client is renamed or
     * a second client needs the same treatment.
     */
    public function up(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->boolean('always_route_atw')->default(false)->after('company_name');
        });
    }

    public function down(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->dropColumn('always_route_atw');
        });
    }
};
