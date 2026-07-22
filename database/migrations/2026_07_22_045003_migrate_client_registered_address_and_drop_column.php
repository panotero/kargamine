<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Addresses now live in client_addresses (multiple per client, each
        // with an address_type), same shape as crm_lead_addresses. The old
        // registered_address was a single free-text blob with no structure
        // to parse, so it's carried over as a best-effort single primary
        // address with the whole blob dumped into address_street.
        $clients = DB::table('client_masters')
            ->whereNotNull('registered_address')
            ->where('registered_address', '!=', '')
            ->get(['id', 'registered_address']);

        foreach ($clients as $client) {
            DB::table('client_addresses')->insert([
                'client_id' => $client->id,
                'address_type' => null,
                'is_primary' => true,
                'address_street' => $client->registered_address,
                'address_country' => 'Philippines',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('client_masters', function (Blueprint $table) {
            $table->dropColumn('registered_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_masters', function (Blueprint $table) {
            $table->text('registered_address')->nullable();
        });
    }
};
