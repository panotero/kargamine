<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->string('signed_document_path')->nullable()->after('status');
            $table->timestamp('signed_at')->nullable()->after('signed_document_path');
            $table->foreignId('decided_by')->nullable()->after('signed_at')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable()->after('decided_by');
            $table->string('decision_remarks')->nullable()->after('decided_at');
        });
    }

    public function down(): void
    {
        Schema::table('client_proposals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('decided_by');
            $table->dropColumn(['signed_document_path', 'signed_at', 'decided_at', 'decision_remarks']);
        });
    }
};
