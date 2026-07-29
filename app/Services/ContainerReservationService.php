<?php

namespace App\Services;

use App\Models\ContainerAsset;
use App\Models\ContainerAssetLocationHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Container availability ranking + reserve/release, shared by
 * ContainerAssetController's HTTP endpoints and BookingController
 * (which reserves containers directly, in-process, while building a
 * booking - not via an internal HTTP call to the other controller).
 */
class ContainerReservationService
{
    /**
     * Restricted to containers currently at $originPortId (a container
     * can't be loaded from a port it isn't at), longest-idle first -
     * this is what both auto-assign and the manual dropdown agree on as
     * "available". No origin filter only when the caller genuinely
     * doesn't know the port yet.
     */
    public function rankedAvailableQuery(int $containerVariantId, ?int $originPortId = null): Builder
    {
        return ContainerAsset::query()
            ->where('container_variant_id', $containerVariantId)
            ->where('status', ContainerAsset::STATUS_AVAILABLE)
            ->when($originPortId, fn ($q, $portId) => $q->where('current_port_id', $portId))
            ->orderBy('last_movement_at');
    }

    /**
     * @throws RuntimeException if fewer than $quantity are available
     */
    public function reserveAuto(int $containerVariantId, ?int $originPortId, int $quantity, ?int $recordedBy): Collection
    {
        $candidates = $this->rankedAvailableQuery($containerVariantId, $originPortId)
            ->lockForUpdate()
            ->limit($quantity)
            ->get();

        if ($candidates->count() < $quantity) {
            throw new RuntimeException('Not enough available containers of this type to auto-assign.');
        }

        return $this->reserveExplicit($candidates->pluck('id')->all(), $recordedBy);
    }

    /**
     * @throws RuntimeException if any target is no longer Available
     */
    public function reserveExplicit(array $assetIds, ?int $recordedBy): Collection
    {
        $assets = ContainerAsset::whereIn('id', $assetIds)->lockForUpdate()->get();

        if ($assets->count() !== count($assetIds) || $assets->contains(fn ($a) => $a->status !== ContainerAsset::STATUS_AVAILABLE)) {
            throw new RuntimeException('One or more selected containers are no longer available.');
        }

        $assets->each(fn (ContainerAsset $asset) => $asset->applyChange(
            ['status' => ContainerAsset::STATUS_BOOKED],
            ContainerAssetLocationHistory::SOURCE_BOOKING_ASSIGNMENT,
            $recordedBy
        ));

        return ContainerAsset::whereIn('id', $assetIds)->get();
    }

    /**
     * @throws RuntimeException if any target isn't currently Booked
     */
    public function release(array $assetIds, ?int $recordedBy): Collection
    {
        $assets = ContainerAsset::whereIn('id', $assetIds)->lockForUpdate()->get();

        if ($assets->contains(fn ($a) => $a->status !== ContainerAsset::STATUS_BOOKED)) {
            throw new RuntimeException('One or more selected containers are not currently booked.');
        }

        $assets->each(fn (ContainerAsset $asset) => $asset->applyChange(
            ['status' => ContainerAsset::STATUS_AVAILABLE],
            ContainerAssetLocationHistory::SOURCE_BOOKING_RELEASE,
            $recordedBy
        ));

        return ContainerAsset::whereIn('id', $assetIds)->get();
    }
}
