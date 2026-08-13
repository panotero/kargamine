<?php

namespace Database\Seeders;

use App\Models\Container;
use Illuminate\Database\Seeder;

class ContainerCatalogSeeder extends Seeder
{
    /**
     * Containers, each with its own Classes and Sizes (the "Containers"
     * tab on the Maintenance page) - Container::syncCatalog() generates
     * the class/size Variants from these, including a "base" (no class)
     * variant per size.
     *
     * Container Types (the container_type table) is intentionally left
     * alone here - containers.container_type_id was added in
     * 2026_07_08_221636_create_containers_table then dropped again in
     * 2026_07_09_000939_drop_column_from_container_table, so Container has
     * no live link to it anymore (Container::type() is dead code).
     */
    public function run(): void
    {
        $containers = [
            ['code' => 'CV', 'name' => 'Container Van', 'sizes' => ['20FT', '40FT'], 'classes' => ['Standard', 'High Cube']],
            ['code' => 'RF', 'name' => 'Reefer Van', 'sizes' => ['20FT', '40FT'], 'classes' => []],
            ['code' => 'FR', 'name' => 'Flat Rack', 'sizes' => ['20FT', '40FT'], 'classes' => []],
            // Loose Cargo / Rolling Cargo don't have a fixed size - they vary
            // and are priced by class only (MT/CBM).
            ['code' => 'LC', 'name' => 'Loose Cargo', 'sizes' => [], 'classes' => ['MT', 'CBM']],
            ['code' => 'RC', 'name' => 'Rolling Cargo', 'sizes' => [], 'classes' => ['MT', 'CBM']],
        ];

        foreach ($containers as $definition) {
            $container = Container::updateOrCreate(
                ['code' => $definition['code']],
                ['name' => $definition['name'], 'is_active' => true]
            );

            $container->syncCatalog($definition['classes'], $definition['sizes']);
        }
    }
}
