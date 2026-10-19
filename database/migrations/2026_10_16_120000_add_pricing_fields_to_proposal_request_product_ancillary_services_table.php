<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Turns ancillary services from a pure reference list into a priced line
 * item: special_charge_id anchors the row to the Special Charges catalog
 * (app/Models/SpecialCharge.php - name + base_value), base_value/quantity
 * are snapshotted at add time and independently editable later from the
 * Build Rate Matrix tab, adjustment is Build-Matrix-only (never shown on
 * the Products tab popup), total_value is server-computed on every save.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_request_product_ancillary_services', function (Blueprint $table) {
            $table->foreignId('special_charge_id')->nullable()->after('ancillary_type')
                ->constrained('special_charges', 'special_charge_id', 'prpanc_charge_fk')->nullOnDelete();
            $table->decimal('base_value', 12, 2)->nullable()->after('special_charge_id');
            $table->decimal('quantity', 12, 2)->nullable()->default(1)->after('base_value');
            $table->decimal('adjustment', 12, 2)->nullable()->default(0)->after('quantity');
            $table->decimal('total_value', 12, 2)->nullable()->after('adjustment');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_request_product_ancillary_services', function (Blueprint $table) {
            $table->dropForeign('prpanc_charge_fk');
            $table->dropColumn(['special_charge_id', 'base_value', 'quantity', 'adjustment', 'total_value']);
        });
    }
};
