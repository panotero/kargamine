<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractRate;
use App\Models\DeliveryType;
use App\Models\HandlingFee;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\Port;
use App\Models\PortCharge;
use App\Models\TruckingTariff;
use App\Models\VatRate;
use Carbon\Carbon;
use RuntimeException;

/**
 * Resolves the full rate breakdown for a booking:
 * lane tariff (FRT/BSC/RA/GRI) -> optional contract discount on FRT only
 * -> port charges -> handling -> trucking -> VAT -> grand total.
 *
 * Falls back to the standard tariff automatically whenever no signed
 * contract or no matching contract_rates line exists for the given
 * lead + lane + container combination.
 */
class RateResolutionService
{
    /**
     * @param  array{
     *     origin_port_id:int, destination_port_id:int,
     *     origin_area_id:int, destination_area_id:int,
     *     delivery_type_id:int,
     *     container_class:int, container_type:int, container_size:int,
     *     origin_service_type:int, destination_service_type:int,
     *     quantity?:int, lead_id?:int|null, booking_date?:string
     * }  $input
     */
    public function resolve(array $input): array
    {
        $bookingDate = isset($input['booking_date'])
            ? Carbon::parse($input['booking_date'])
            : Carbon::today();

        $lane = Lane::where('origin_port_id', $input['origin_port_id'])
            ->where('destination_port_id', $input['destination_port_id'])
            ->where('is_active', true)
            ->first();

        if (! $lane) {
            throw new RuntimeException('No active lane found for the selected origin and destination ports.');
        }

        $tariffRate = LaneTariffRate::where('lane_id', $lane->lane_id)
            ->activeOn($bookingDate)
            ->orderByDesc('effective_date')
            ->first();

        if (! $tariffRate) {
            throw new RuntimeException("No active tariff rate found for this lane on {$bookingDate->toDateString()}.");
        }

        $vatRate = VatRate::activeOn($bookingDate)->orderByDesc('effective_date')->first();

        if (! $vatRate) {
            throw new RuntimeException('No active VAT rate configured.');
        }

        [$discountType, $discountValue, $contract, $contractRate] = $this->resolveContractDiscount($input, $bookingDate);

        $frt = (float) $tariffRate->frt;
        $frtAfterDiscount = $this->applyDiscount($frt, $discountType, $discountValue);

        $bsc = (float) $tariffRate->bsc;
        $totalAdjustment = (float) $tariffRate->ra + (float) $tariffRate->gri;
        $art = $frtAfterDiscount + $bsc + $totalAdjustment;

        $originPortCharges = PortCharge::where('port_id', $input['origin_port_id'])
            ->activeOn($bookingDate)->get();
        $destinationPortCharges = PortCharge::where('port_id', $input['destination_port_id'])
            ->activeOn($bookingDate)->get();

        $portChargesTotal = $originPortCharges->sum('amount') + $destinationPortCharges->sum('amount');

        $originHandling = HandlingFee::where('port_id', $input['origin_port_id'])->activeOn($bookingDate)->first();
        $destinationHandling = HandlingFee::where('port_id', $input['destination_port_id'])->activeOn($bookingDate)->first();
        $handlingTotal = (float) ($originHandling->amount ?? 0) + (float) ($destinationHandling->amount ?? 0);

        $deliveryType = DeliveryType::findOrFail($input['delivery_type_id']);

        $originTrucking = 0.0;
        $destinationTrucking = 0.0;

        if ($deliveryType->includes_origin_trucking) {
            $originTrucking = (float) (TruckingTariff::where('area_id', $input['origin_area_id'])
                ->where('delivery_type_id', $deliveryType->delivery_type_id)
                ->activeOn($bookingDate)
                ->value('amount') ?? 0);
        }

        if ($deliveryType->includes_destination_trucking) {
            $destinationTrucking = (float) (TruckingTariff::where('area_id', $input['destination_area_id'])
                ->where('delivery_type_id', $deliveryType->delivery_type_id)
                ->activeOn($bookingDate)
                ->value('amount') ?? 0);
        }

        $truckingTotal = $originTrucking + $destinationTrucking;

        $vatableAmount = $art + $portChargesTotal + $handlingTotal + $truckingTotal;
        $vatAmount = round($vatableAmount * ((float) $vatRate->rate_percent / 100), 2);
        $grandTotal = round($vatableAmount + $vatAmount, 2);

        return [
            'lane_id' => $lane->lane_id,
            'tariff_rate_id' => $tariffRate->rate_id,
            'vat_rate_id' => $vatRate->vat_rate_id,
            'contract_id' => $contract?->id,
            'contract_rate_id' => $contractRate?->id,

            'frt' => round($frt, 2),
            'discount_type' => $discountType,
            'discount_value' => round($discountValue, 2),
            'frt_after_discount' => round($frtAfterDiscount, 2),
            'bsc' => round($bsc, 2),
            'ra' => round((float) $tariffRate->ra, 2),
            'gri' => round((float) $tariffRate->gri, 2),
            'total_adjustment' => round($totalAdjustment, 2),
            'art' => round($art, 2),

            'port_charges' => [
                'origin' => $originPortCharges->map(fn ($c) => [
                    'charge_type_id' => $c->charge_type_id,
                    'amount' => (float) $c->amount,
                ])->values()->all(),
                'destination' => $destinationPortCharges->map(fn ($c) => [
                    'charge_type_id' => $c->charge_type_id,
                    'amount' => (float) $c->amount,
                ])->values()->all(),
                'total' => round($portChargesTotal, 2),
            ],

            'handling' => [
                'origin' => (float) ($originHandling->amount ?? 0),
                'destination' => (float) ($destinationHandling->amount ?? 0),
                'total' => round($handlingTotal, 2),
            ],

            'trucking' => [
                'origin' => round($originTrucking, 2),
                'destination' => round($destinationTrucking, 2),
                'total' => round($truckingTotal, 2),
            ],

            'vat_rate_percent' => (float) $vatRate->rate_percent,
            'vat_amount' => $vatAmount,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * @return array{0: ?string, 1: float, 2: ?Contract, 3: ?ContractRate}
     */
    protected function resolveContractDiscount(array $input, Carbon $bookingDate): array
    {
        if (empty($input['lead_id'])) {
            return [null, 0.0, null, null];
        }

        $contract = Contract::where('lead_id', $input['lead_id'])
            ->active()
            ->validOn($bookingDate)
            ->first();

        if (! $contract) {
            return [null, 0.0, null, null];
        }

        $originPort = Port::find($input['origin_port_id']);
        $destinationPort = Port::find($input['destination_port_id']);

        $contractRate = ContractRate::where('contract_id', $contract->id)
            ->where('route_from', $originPort?->code)
            ->where('route_to', $destinationPort?->code)
            ->where('container_class', $input['container_class'])
            ->where('container_type', $input['container_type'])
            ->where('container_size', $input['container_size'])
            ->where('origin_service_type', $input['origin_service_type'])
            ->where('destination_service_type', $input['destination_service_type'])
            ->where('min_van_qty', '<=', $input['quantity'] ?? 1)
            ->where('is_active', true)
            ->orderByDesc('min_van_qty') // pick the highest qualifying volume tier
            ->first();

        if (! $contractRate) {
            // Contract exists but no matching line -> fall back to standard tariff.
            return [null, 0.0, $contract, null];
        }

        return [$contractRate->discount_type, (float) $contractRate->discount_value, $contract, $contractRate];
    }

    protected function applyDiscount(float $frt, ?string $discountType, float $discountValue): float
    {
        if (! $discountType || $discountValue <= 0) {
            return $frt;
        }

        return $discountType === ContractRate::DISCOUNT_PERCENTAGE
            ? $frt - ($frt * $discountValue / 100)
            : max(0, $frt - $discountValue);
    }
}
