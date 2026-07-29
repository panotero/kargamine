<?php

namespace Database\Seeders;

use App\Models\ContainerAsset;
use App\Models\ContainerVariant;
use App\Models\Port;
use Illuminate\Database\Seeder;

class ContainerAssetSeeder extends Seeder
{
    /** 8 units per seeded container/class/size variant - the Container Inventory page's test fleet. */
    private const UNITS_PER_VARIANT = 8;

    private const OWNER_PREFIXES = ['MSCU', 'MAEU', 'CMAU', 'HLXU', 'ONEU', 'EGHU', 'TCLU', 'OOLU'];

    /**
     * Roughly matches a real fleet's status mix - mostly Available, with
     * enough of the other statuses to actually exercise every Container
     * Inventory filter and ContainerReservationService's availability query.
     */
    private const STATUS_WEIGHTS = [
        ContainerAsset::STATUS_AVAILABLE => 60,
        ContainerAsset::STATUS_BOOKED => 15,
        ContainerAsset::STATUS_IN_TRANSIT => 10,
        ContainerAsset::STATUS_UNDER_REPAIR => 7,
        ContainerAsset::STATUS_DAMAGED => 5,
        ContainerAsset::STATUS_OUT_OF_SERVICE => 3,
    ];

    public function run(): void
    {
        $variants = ContainerVariant::all();
        $portIds = Port::whereIn('code', LaneSeeder::SAMPLE_PORT_CODES)->pluck('port_id')->values();

        if ($variants->isEmpty() || $portIds->isEmpty()) {
            $this->command?->warn('ContainerAssetSeeder: no container variants/ports found - run ContainerCatalogSeeder and LaneSeeder first.');

            return;
        }

        // Shuffled once so cycling through it via seq % count actually mixes
        // statuses across the run - left in weighted (unshuffled) order, the
        // first ~56 slots would all land in the largest (Available) block.
        $weightedStatuses = collect(self::STATUS_WEIGHTS)
            ->flatMap(fn ($weight, $status) => array_fill(0, $weight, (int) $status))
            ->shuffle()
            ->values();

        $seq = 0;

        foreach ($variants as $variant) {
            for ($unit = 0; $unit < self::UNITS_PER_VARIANT; $unit++) {
                $seq++;
                $prefix = self::OWNER_PREFIXES[$seq % count(self::OWNER_PREFIXES)];
                $containerNo = sprintf('%s%07d', $prefix, $seq);

                ContainerAsset::updateOrCreate(
                    ['container_no' => $containerNo],
                    [
                        'container_variant_id' => $variant->id,
                        'status' => $weightedStatuses[$seq % $weightedStatuses->count()],
                        'current_port_id' => $portIds[$seq % $portIds->count()],
                        'last_movement_at' => now()->subDays($seq % 30),
                    ]
                );
            }
        }
    }
}
