<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Supporting document (MSDS, dangerous goods declaration, etc.) for a
     * cargo line flagged is_hazardous - uploaded via FileUploadService and
     * stored as a path/URL, same pattern as the EIR checklist/photo uploads.
     */
    public function up(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->string('hazardous_document_path')->nullable()->after('is_fragile');
        });
    }

    public function down(): void
    {
        Schema::table('booking_lines', function (Blueprint $table) {
            $table->dropColumn('hazardous_document_path');
        });
    }
};
