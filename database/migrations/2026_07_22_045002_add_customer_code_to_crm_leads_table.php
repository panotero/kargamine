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
        Schema::table('crm_leads', function (Blueprint $table) {
            // Reserved once (via ClientMaster::generateNextCustomerCode()) the
            // first time a "Create Client Master" flow is opened for this
            // lead, so the code stays locked/stable across sessions instead
            // of being regenerated every time.
            $table->string('customer_code')->nullable()->unique()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn('customer_code');
        });
    }
};
