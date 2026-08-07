<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(UserSeeder::class);
        $this->call([
            // template seeder
            NavIconSeeder::class,
            NavMenuSeeder::class,
            UserSeeder::class,
            Setting_roleSeeder::class,
            PermissionSeeder::class,

            // kargamine seeder
            CrmStatusSeeder::class,
            lovSeeder::class,
            PortSeeder::class,
            ServiceableAreaSeeder::class,
            DeliveryTypeSeeder::class,
            departmentSeeder::class,
            userstatusSeeder::class,
            AddressTypeSeeder::class,
            LeadSourceSeeder::class,
            BusinessTypeSeeder::class,
            IndustrySeeder::class,
            OrganizationTypeSeeder::class,
            ClientCategorySeeder::class,
            ClientClassificationSeeder::class,
            UnitSeeder::class,
            SpecialChargeSeeder::class,
            CargoYardSeeder::class,

            // maintenance page sample data - rate/master data tables
            ContainerCatalogSeeder::class,
            ChargeTypeSeeder::class,
            LaneSeeder::class,
            LaneTariffRateSeeder::class,
            PortChargeSeeder::class,
            GeneralChargeSeeder::class,
            HandlingFeeSeeder::class,
            TruckingTariffSeeder::class,
            VatRateSeeder::class,

            // container inventory - physical fleet (depends on
            // ContainerCatalogSeeder for variants, LaneSeeder for ports)
            ContainerAssetSeeder::class,
        ]);
    }
}
