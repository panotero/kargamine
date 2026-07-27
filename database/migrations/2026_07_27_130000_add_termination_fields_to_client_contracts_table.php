<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_contracts', function (Blueprint $table) {
            $table->text('terminated_reason')->nullable()->after('signed_document_path');
            $table->unsignedBigInteger('terminated_by')->nullable()->after('terminated_reason');
            $table->timestamp('terminated_at')->nullable()->after('terminated_by');

            $table->foreign('terminated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('client_contracts', function (Blueprint $table) {
            $table->dropForeign(['terminated_by']);
            $table->dropColumn(['terminated_reason', 'terminated_by', 'terminated_at']);
        });
    }
};
