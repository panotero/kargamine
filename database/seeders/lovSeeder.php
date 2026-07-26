<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class lovSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            ['route' => 'BUTUAN', 'port' => 'BUTUAN'],
            ['route' => 'CEBU', 'port' => 'CEBU'],
            ['route' => 'CAGAYAN', 'port' => 'CAGAYAN'],
            ['route' => 'DAVAO', 'port' => 'DAVAO'],
            ['route' => 'DUMAGUETE', 'port' => 'DUMAGUETE'],
            ['route' => 'GEN SAN', 'port' => 'GEN SAN'],
            ['route' => 'ILIGAN', 'port' => 'ILIGAN'],
            ['route' => 'ILOILO', 'port' => 'ILOILO'],
            ['route' => 'OSAMIS', 'port' => 'OSAMIS'],
            ['route' => 'CORON', 'port' => 'CORON'],
            ['route' => 'ROXAS', 'port' => 'ROXAS'],
            ['route' => 'CATICLAN', 'port' => 'CATICLAN'],
            ['route' => 'ORMOC', 'port' => 'ORMOC'],
            ['route' => 'TAGBILARAN', 'port' => 'TAGBILARAN'],
            ['route' => 'TACLOBAN', 'port' => 'TACLOBAN'],
            ['route' => 'ZAMBOANGA', 'port' => 'ZAMBOANGA'],
            ['route' => 'PUERTO PRINCESSA', 'port' => 'PUERTO PRINCESSA'],
            ['route' => 'SURIGAO', 'port' => 'SURIGAO'],
            ['route' => 'COTABATO', 'port' => 'COTABATO'],
            ['route' => 'BATANGAS', 'port' => 'BATANGAS'],
            ['route' => 'MANILA', 'port' => 'MANILA'],
        ];
        foreach ($routes as $row) {
            DB::table('routes')->updateOrInsert(
                ['route' => $row['route']],
                ['port' => $row['port'], 'updated_at' => now()]
            );
        }

        $containerTypes = [
            'CONVAN', 'FLATRACK (PLATFORM)', 'REEFER', 'HIGH CUBE', 'CATTLE VAN',
            'TANK (ISO TANK)', 'ROLLING CARGO', 'SPECIAL CONTAINERS', 'OPEN-TOP VAN',
        ];
        foreach ($containerTypes as $type) {
            DB::table('container_type')->updateOrInsert(
                ['type' => $type],
                ['updated_at' => now()]
            );
        }

        $serviceTypes = [
            ['type' => 'ORIGIN', 'mode' => 'DOOR'],
            ['type' => 'ORIGIN', 'mode' => 'PIER-STUFFING'],
            ['type' => 'ORIGIN', 'mode' => 'PIER-VANOUT'],
            ['type' => 'DESTINATION', 'mode' => 'DOOR'],
            ['type' => 'DESTINATION', 'mode' => 'PIER-STRIPPING'],
            ['type' => 'DESTINATION', 'mode' => 'PIER-VAN OUT'],
        ];
        foreach ($serviceTypes as $row) {
            DB::table('service_type')->updateOrInsert(
                ['type' => $row['type'], 'mode' => $row['mode']],
                ['updated_at' => now()]
            );
        }

        $proposalStatuses = ['Pending', 'Approved', 'Disapproved', 'Accepted', 'Rejected', 'On-Hold'];
        foreach ($proposalStatuses as $status) {
            DB::table('proposal_status')->updateOrInsert(
                ['status' => $status],
                ['updated_at' => now()]
            );
        }

        $customerTypes = ['SHIPPER', 'CONSIGNEE', 'SHIPPER-CONSIGNEE'];
        foreach ($customerTypes as $type) {
            DB::table('customer_type')->updateOrInsert(
                ['type' => $type],
                ['updated_at' => now()]
            );
        }

        $containerClasses = ['A', 'B', 'C', 'D'];
        foreach ($containerClasses as $class) {
            DB::table('container_class')->updateOrInsert(
                ['class' => $class],
                ['updated_at' => now()]
            );
        }

        $containerSizes = ['10-FOOTER', '20-FOOTER', '40-FOOTER STD', '40-FOOTER HC'];
        foreach ($containerSizes as $size) {
            DB::table('container_size')->updateOrInsert(
                ['size' => $size],
                ['updated_at' => now()]
            );
        }
    }
}
