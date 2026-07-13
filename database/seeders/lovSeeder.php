<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class lovSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('routes')->insert([
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
        ]);

        DB::table('container_type')->insert([
            ['type' => 'CONVAN'],
            ['type' => 'FLATRACK (PLATFORM)'],
            ['type' => 'REEFER'],
            ['type' => 'HIGH CUBE'],
            ['type' => 'CATTLE VAN'],
            ['type' => 'TANK (ISO TANK)'],
            ['type' => 'ROLLING CARGO'],
            ['type' => 'SPECIAL CONTAINERS'],
            ['type' => 'OPEN-TOP VAN'],
        ]);

        DB::table('service_type')->insert([
            ['type' => 'ORIGIN', 'mode' => 'DOOR'],
            ['type' => 'ORIGIN', 'mode' => 'PIER-STUFFING'],
            ['type' => 'ORIGIN', 'mode' => 'PIER-VANOUT'],
            ['type' => 'DESTINATION', 'mode' => 'DOOR'],
            ['type' => 'DESTINATION', 'mode' => 'PIER-STRIPPING'],
            ['type' => 'DESTINATION', 'mode' => 'PIER-VAN OUT'],
        ]);

        DB::table('proposal_status')->insert([
            ['status' => 'Pending'],
            ['status' => 'Approved'],
            ['status' => 'Disapproved'],
            ['status' => 'Accepted'],
            ['status' => 'Rejected'],
            ['status' => 'On-Hold'],
        ]);

        DB::table('customer_type')->insert([
            ['type' => 'SHIPPER'],
            ['type' => 'CONSIGNEE'],
            ['type' => 'SHIPPER-CONSIGNEE'],
        ]);
        DB::table('container_class')->insert([
            ['class' => 'A'],
            ['class' => 'B'],
            ['class' => 'C'],
            ['class' => 'D'],
        ]);
        DB::table('container_size')->insert([
            ['size' => '10-FOOTER'],
            ['size' => '20-FOOTER'],
            ['size' => '40-FOOTER STD'],
            ['size' => '40-FOOTER HC'],
        ]);
    }
}
