<?php

namespace Database\Seeders;
use App\Models\Location;
use App\Models\Port;
use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    /**
     * Each entry seeds one Location plus its single port, named after the
     * city - rename/regroup these via the Locations & Ports settings UI
     * once real locations (e.g. one location with multiple ports, like
     * Aklan -> Caticlan Port + Dumaguit Port) are known.
     */
    public function run(): void
    {
        $ports = [
            'MANILA', 'BACOLOD PORT', 'BUTUAN', 'CEBU', 'CAGAYAN', 'DAVAO',
            'DUMAGUETE', 'GEN SAN', 'ILIGAN', 'ILOILO', 'OSAMIS', 'CORON',
            'ROXAS', 'CATICLAN', 'ORMOC', 'TAGBILARAN', 'TACLOBAN', 'ZAMBOANGA',
            'PUERTO PRINCESSA', 'SURIGAO', 'COTABATO', 'BATANGAS',
        ];

        // Generate ports until there are 200
        for ($i = count($ports) + 1; $i <= 200; $i++) {
            $ports[] = 'PORT ' . $i;
        }

        foreach ($ports as $name) {
            $location = Location::firstOrCreate(['name' => $name], ['is_active' => true]);

            Port::updateOrCreate(
                ['location_id' => $location->location_id, 'name' => $name],
                ['is_active' => true]
            );
        }
    }
}
