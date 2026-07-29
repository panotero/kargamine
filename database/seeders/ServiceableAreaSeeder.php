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
    private const AREA_BY_PORT_CODE = [
        'MNL' => 'Port Area, Manila',
        'BCD' => 'Banago, Bacolod',
        'BUT' => 'Nasipit',
        'CEB' => 'Cebu City',
        'CGY' => 'Macabalan, Cagayan de Oro',
        'DVO' => 'Sasa, Davao City',
        'DGT' => 'Dumaguete City',
        'GES' => 'Makar, General Santos',
        'ILG' => 'Iligan City',
        'ILO' => 'Iloilo City',
        'OZM' => 'Ozamis City',
        'CRN' => 'Coron, Palawan',
        'ROX' => 'Roxas City',
        'CTC' => 'Caticlan, Malay',
        'ORM' => 'Ormoc City',
        'TAG' => 'Tagbilaran City',
        'TAC' => 'Tacloban City',
        'ZAM' => 'Zamboanga City',
        'PPS' => 'Puerto Princesa City',
        'SUR' => 'Surigao City',
        'COT' => 'Cotabato City',
        'BTG' => 'Bauan',
    ];

    public function run(): void
    {
        Port::all(['port_id', 'code', 'name'])->each(function (Port $port) {
            $areaName = self::AREA_BY_PORT_CODE[$port->code] ?? "{$port->name} Area";

            ServiceableArea::updateOrCreate(
                ['port_id' => $port->port_id, 'area_name' => $areaName],
                ['is_active' => true]
            );
        });
    }
}
