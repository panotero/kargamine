<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guarded with hasColumn/hasTable checks: MySQL DDL isn't
        // transactional, so a mid-way failure during development left this
        // table in a partially-migrated state - re-running must be safe.
        if (Schema::hasColumn('client_finance', 'cro_user_id')) {
            Schema::table('client_finance', function (Blueprint $table) {
                // SQLite (used by the test suite) doesn't support dropping a
                // named foreign key constraint - dropColumn() alone still
                // removes it there (table is rebuilt under the hood). MySQL
                // still needs the explicit dropForeign() first.
                if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                    $table->dropForeign(['cro_user_id']);
                }
                $table->dropColumn('cro_user_id');
            });
        }

        if (Schema::hasColumn('client_finance', 'max_declared_value')) {
            Schema::table('client_finance', function (Blueprint $table) {
                $table->dropColumn('max_declared_value');
            });
        }

        if (! Schema::hasColumn('client_finance', 'cro')) {
            Schema::table('client_finance', function (Blueprint $table) {
                // "CRO" actually means Cargo Release Order (Manual or
                // Automatic), not a Credit Officer user - see the
                // cro_user_id column it replaces.
                $table->string('cro')->nullable()->after('mode_of_payment');
            });
        }

        // Max Declared Value is no longer a single figure - a client can
        // declare a maximum value per commodity type, repeatable.
        if (! Schema::hasTable('client_commodity_declared_values')) {
            Schema::create('client_commodity_declared_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained('client_masters')->cascadeOnDelete();
                $table->string('commodity_type');
                $table->decimal('max_declared_value', 15, 2)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_commodity_declared_values');

        Schema::table('client_finance', function (Blueprint $table) {
            $table->dropColumn('cro');
            $table->foreignId('cro_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('max_declared_value', 15, 2)->nullable();
        });
    }
};
