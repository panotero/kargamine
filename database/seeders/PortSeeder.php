<?php

namespace Database\Seeders;

use App\Models\Port;
use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    /**
     * Codes are 3-letter abbreviations I picked for each port name -
     * rename any of these before running if you'd rather match an
     * existing convention (e.g. official PPA port codes).
     */
    public function run(): void
    {
        $ports = [
            ['code' => 'MNL', 'name' => 'MANILA'],
            ['code' => 'BCD', 'name' => 'BACOLOD PORT'],
            ['code' => 'BUT', 'name' => 'BUTUAN'],
            ['code' => 'CEB', 'name' => 'CEBU'],
            ['code' => 'CGY', 'name' => 'CAGAYAN'],
            ['code' => 'DVO', 'name' => 'DAVAO'],
            ['code' => 'DGT', 'name' => 'DUMAGUETE'],
            ['code' => 'GES', 'name' => 'GEN SAN'],
            ['code' => 'ILG', 'name' => 'ILIGAN'],
            ['code' => 'ILO', 'name' => 'ILOILO'],
            ['code' => 'OZM', 'name' => 'OSAMIS'],
            ['code' => 'CRN', 'name' => 'CORON'],
            ['code' => 'ROX', 'name' => 'ROXAS'],
            ['code' => 'CTC', 'name' => 'CATICLAN'],
            ['code' => 'ORM', 'name' => 'ORMOC'],
            ['code' => 'TAG', 'name' => 'TAGBILARAN'],
            ['code' => 'TAC', 'name' => 'TACLOBAN'],
            ['code' => 'ZAM', 'name' => 'ZAMBOANGA'],
            ['code' => 'PPS', 'name' => 'PUERTO PRINCESSA'],
            ['code' => 'SUR', 'name' => 'SURIGAO'],
            ['code' => 'COT', 'name' => 'COTABATO'],
            ['code' => 'BTG', 'name' => 'BATANGAS'],
        ];

        // Generate ports until there are 200
        // for ($i = count($ports) + 1; $i <= 200; $i++) {
        //     $ports[] = [
        //         'code' => sprintf('P%03d', $i),   // P023, P024 ... P200
        //         'name' => 'PORT ' . $i,
        //     ];
        // }

        foreach ($ports as $port) {
            Port::updateOrCreate(
                ['code' => $port['code']],
                [
                    'name' => $port['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
