<?php

namespace Database\Seeders;

use App\Models\Container;
use App\Models\ContainerClass;
use App\Models\ContainerSize;
use App\Models\ContainerVariant;
use Illuminate\Database\Seeder;

class ContainerCatalogSeeder extends Seeder
{
    /**
     * Container Classes, Sizes, Containers and their Class/Size Variants -
     * the "Containers" family of tabs on the Maintenance page.
     *
     * Container Types (the container_type table) is intentionally left
     * alone here - containers.container_type_id was added in
     * 2026_07_08_221636_create_containers_table then dropped again in
     * 2026_07_09_000939_drop_column_from_container_table, so Container has
     * no live link to it anymore (Container::type() is dead code).
     */
    public function run(): void
    {
        $classes = collect(['Standard', 'High Cube'])
            ->mapWithKeys(fn ($class) => [$class => ContainerClass::firstOrCreate(['class' => $class])]);

        $sizes = collect(['20FT', '40FT'])
            ->mapWithKeys(fn ($size) => [$size => ContainerSize::firstOrCreate(['size' => $size])]);

        $containers = [
            ['code' => 'CV', 'name' => 'Container Van', 'variants' => [['Standard', '20FT'], ['Standard', '40FT'], ['High Cube', '40FT']]],
            ['code' => 'RF', 'name' => 'Reefer Van', 'variants' => [['Standard', '20FT'], ['Standard', '40FT']]],
            ['code' => 'FR', 'name' => 'Flat Rack', 'variants' => [['Standard', '20FT'], ['Standard', '40FT']]],
        ];

        foreach ($containers as $definition) {
            $container = Container::updateOrCreate(
                ['code' => $definition['code']],
                ['name' => $definition['name'], 'is_active' => true]
            );

            foreach ($definition['variants'] as [$className, $sizeName]) {
                ContainerVariant::firstOrCreate([
                    'container_id' => $container->id,
                    'container_class_id' => $classes[$className]->id,
                    'container_size_id' => $sizes[$sizeName]->id,
                ], [
                    'is_active' => true,
                ]);
            }
        }
    }
}
