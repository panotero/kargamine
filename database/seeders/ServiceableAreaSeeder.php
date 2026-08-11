<?php

namespace Database\Seeders;

use App\Models\Port;
use App\Models\ServiceableArea;
use Illuminate\Database\Seeder;

class ServiceableAreaSeeder extends Seeder
{
    /**
     * One starter serviceable area per port - the municipality/barangay
     * the pier actually sits in, where that's known (e.g. BTG -> Bauan,
     * matching the operations process doc's "Batangas - Frabelle, Bauan").
     * Ports not listed here (including the generated filler ports from
     * PortSeeder) fall back to "{port name} Area". Add more areas per
     * port via the Serviceable Areas admin page as real trucking zones
     * are defined - this just guarantees every port has at least one.
     */
    private const AREA_BY_PORT_NAME = [
        'MANILA' => 'Port Area, Manila',
        'BACOLOD PORT' => 'Banago, Bacolod',
        'BUTUAN' => 'Nasipit',
        'CEBU' => 'Cebu City',
        'CAGAYAN' => 'Macabalan, Cagayan de Oro',
        'DAVAO' => 'Sasa, Davao City',
        'DUMAGUETE' => 'Dumaguete City',
        'GEN SAN' => 'Makar, General Santos',
        'ILIGAN' => 'Iligan City',
        'ILOILO' => 'Iloilo City',
        'OSAMIS' => 'Ozamis City',
        'CORON' => 'Coron, Palawan',
        'ROXAS' => 'Roxas City',
        'CATICLAN' => 'Caticlan, Malay',
        'ORMOC' => 'Ormoc City',
        'TAGBILARAN' => 'Tagbilaran City',
        'TACLOBAN' => 'Tacloban City',
        'ZAMBOANGA' => 'Zamboanga City',
        'PUERTO PRINCESSA' => 'Puerto Princesa City',
        'SURIGAO' => 'Surigao City',
        'COTABATO' => 'Cotabato City',
        'BATANGAS' => 'Bauan',
    ];

    public function run(): void
    {
        Port::all(['port_id', 'name'])->each(function (Port $port) {
            $areaName = self::AREA_BY_PORT_NAME[$port->name] ?? "{$port->name} Area";

            ServiceableArea::updateOrCreate(
                ['port_id' => $port->port_id, 'area_name' => $areaName],
                ['is_active' => true]
            );
        });
    }
}
