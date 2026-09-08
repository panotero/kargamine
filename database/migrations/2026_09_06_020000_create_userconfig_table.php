<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * UserConfigController/UserConfig have existed against this table name
     * for a while, but no migration for it was ever committed - the "Approval
     * Roles" tab 500s without this.
     */
    public function up(): void
    {
        Schema::create('userconfig_table', function (Blueprint $table) {
            $table->id();
            $table->string('designation', 100);
            $table->enum('approval_type', ['routing', 'pre-approval', 'final-approval']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('userconfig_table');
    }
};
