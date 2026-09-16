<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->timestamp('signature_requested_at')->nullable()->after('signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->dropColumn('signature_requested_at');
        });
    }
};
