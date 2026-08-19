<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * container_variants already allows a null container_class_id (a container
     * with no configured classes gets a class-less "base" variant) and a null
     * container_size_id (Loose Cargo / Rolling Cargo have no fixed size and are
     * priced by class instead). The rate-storage tables that snapshot a
     * resolved variant's class/size must allow the same nulls, otherwise
     * saving a base variant into a proposal, contract, or booking line hits a
     * NOT NULL constraint violation.
     */
    public function up(): void
    {
        Schema::table('client_proposal_rates', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable()->change();
            $table->foreignId('container_size_id')->nullable()->change();
        });

        Schema::table('client_contract_rates', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable()->change();
            $table->foreignId('container_size_id')->nullable()->change();
        });

        Schema::table('booking_lines', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable()->change();
            $table->foreignId('container_size_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('client_proposal_rates', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable(false)->change();
            $table->foreignId('container_size_id')->nullable(false)->change();
        });

        Schema::table('client_contract_rates', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable(false)->change();
            $table->foreignId('container_size_id')->nullable(false)->change();
        });

        Schema::table('booking_lines', function (Blueprint $table) {
            $table->foreignId('container_class_id')->nullable(false)->change();
            $table->foreignId('container_size_id')->nullable(false)->change();
        });
    }
};
