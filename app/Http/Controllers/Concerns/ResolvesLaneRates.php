<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Container;
use App\Models\ContainerVariant;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;

/**
 * Shared lane/tariff + container-variant resolution used by both the
 * signed-proposal flow (ClientProposalController) and the Request-for-
 * Proposal wizard's Products tab (ProposalRequestProductController).
 */
trait ResolvesLaneRates
{
    /**
     * Resolves the active lane -> active-on-date tariff rate -> priced
     * variant row for the given origin/destination/container variant,
     * returning ['frt' => (float)] if a price exists, or [] if any step
     * (lane, tariff rate, or price) is missing.
     */
    protected function resolveRate($originPortId, $destinationPortId, $containerVariantId): array
    {
        $lane = Lane::where('origin_port_id', $originPortId)
            ->where('destination_port_id', $destinationPortId)
            ->where('is_active', true)->first();
        if (! $lane) {
            return [];
        }

        $tariffRate = LaneTariffRate::where('lane_id', $lane->lane_id)
            ->activeOn()->orderByDesc('effective_date')->first();
        if (! $tariffRate) {
            return [];
        }

        $price = LaneTariffRatePrice::where('lane_tariff_rate_id', $tariffRate->rate_id)
            ->where('container_variant_id', $containerVariantId)->first();

        return $price ? ['frt' => (float) $price->frt] : [];
    }

    /**
     * Resolves the ContainerVariant matching a container type code plus an
     * optional size/class. Matching tolerates null class and/or size on
     * both sides: a container that has no classes (e.g. Flat Rack, Reefer
     * Van) or no sizes (Loose Cargo, Rolling Cargo - priced by class only)
     * will only ever have variants with that side null, so the lookup
     * matches null to null rather than requiring both to be set.
     */
    protected function resolveContainerVariant($containerTypeCode, $containerSizeId, $containerClassId = null)
    {
        $container = Container::where('code', $containerTypeCode)->first();
        if (! $container) {
            return null;
        }

        return ContainerVariant::where('container_id', $container->id)
            ->where(function ($q) use ($containerClassId) {
                $containerClassId
                    ? $q->where('container_class_id', $containerClassId)
                    : $q->whereNull('container_class_id');
            })
            ->where(function ($q) use ($containerSizeId) {
                $containerSizeId
                    ? $q->where('container_size_id', $containerSizeId)
                    : $q->whereNull('container_size_id');
            })
            ->first();
    }
}
