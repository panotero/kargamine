<?php

namespace App\Services;

use App\Models\ClientContractRate;
use App\Models\DeliveryType;
use App\Models\HandlingFee;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;
use App\Models\PortCharge;
use App\Models\TruckingTariff;
use App\Models\VatRate;
use Carbon\Carbon;
use RuntimeException;

/**
 * Resolves the full multi-line rate breakdown for a booking. Route and
 * delivery mode are per cargo line now (different lines in one booking
 * can go to different destinations), so each line resolves its own lane,
 * tariff, delivery type, port charges, handling and trucking - the
 * booking only aggregates those into totals and applies VAT once, on the
 * booking-wide vatable amount.
 *
 * Falls back to the standard tariff automatically whenever no active
 * client contract, or no matching client_contract_rates line, exists
 * for a given line's route + container variant.
 */
class RateResolutionService
{
    /**
     * @param  array{booking_date?: string, client_contract_id?: int|null}  $header
     * @param  array<int, array{
     *     container_id:int, container_class_id:int, container_size_id:int,
     *     container_variant_id:int, quantity:int,
     *     origin_port_id:int, destination_port_id:int,
     *     origin_area_id:int, destination_area_id:int,
     *     origin_mode:string, destination_mode:string
     * }>  $lines
     */
    public function resolve(array $header, array $lines): array
    {
        if (empty($lines)) {
            throw new RuntimeException('A booking needs at least one cargo line.');
        }

        $bookingDate = isset($header['booking_date'])
            ? Carbon::parse($header['booking_date'])
            : Carbon::today();

        $vatRate = VatRate::activeOn($bookingDate)->where('tax_type', 'General')->orderByDesc('effective_date')->first();

        if (! $vatRate) {
            throw new RuntimeException('No active VAT rate configured.');
        }

        $resolvedLines = [];
        $linesTotal = 0.0;
        $portChargesTotal = 0.0;
        $handlingTotal = 0.0;
        $truckingTotal = 0.0;

        foreach ($lines as $index => $line) {
            $lineNumber = $index + 1;

            $lane = Lane::where('origin_port_id', $line['origin_port_id'])
                ->where('destination_port_id', $line['destination_port_id'])
                ->where('is_active', true)
                ->first();

            if (! $lane) {
                throw new RuntimeException("Line {$lineNumber}: no active lane found for the selected origin and destination ports.");
            }

            $tariffRate = LaneTariffRate::where('lane_id', $lane->lane_id)
                ->activeOn($bookingDate)
                ->orderByDesc('effective_date')
                ->first();

            if (! $tariffRate) {
                throw new RuntimeException("Line {$lineNumber}: no active tariff rate found for this lane on {$bookingDate->toDateString()}.");
            }

            $price = LaneTariffRatePrice::where('lane_tariff_rate_id', $tariffRate->rate_id)
                ->where('container_variant_id', $line['container_variant_id'])
                ->first();

            if (! $price) {
                throw new RuntimeException("Line {$lineNumber}: no tariff price configured for this lane and container variant.");
            }

            $deliveryType = DeliveryType::where('includes_origin_trucking', $line['origin_mode'] === 'door')
                ->where('includes_destination_trucking', $line['destination_mode'] === 'door')
                ->first();

            if (! $deliveryType) {
                throw new RuntimeException("Line {$lineNumber}: no delivery type configured for that origin/destination mode combination.");
            }

            $frt = (float) $price->frt;
            [$discountType, $discountValue] = $this->resolveLineDiscount($header, $line);
            $frtAfterDiscount = $this->applyDiscount($frt, $discountType, $discountValue);
            $quantity = max(1, (int) $line['quantity']);
            $lineTotal = round($frtAfterDiscount * $quantity, 2);
            $linesTotal += $lineTotal;

            $originPortCharges = PortCharge::where('port_id', $line['origin_port_id'])->activeOn($bookingDate)->get();
            $destinationPortCharges = PortCharge::where('port_id', $line['destination_port_id'])->activeOn($bookingDate)->get();
            $linePortChargesTotal = $originPortCharges->sum('amount') + $destinationPortCharges->sum('amount');
            $portChargesTotal += $linePortChargesTotal;

            $originHandling = HandlingFee::where('port_id', $line['origin_port_id'])->activeOn($bookingDate)->first();
            $destinationHandling = HandlingFee::where('port_id', $line['destination_port_id'])->activeOn($bookingDate)->first();
            $lineHandlingTotal = (float) ($originHandling->amount ?? 0) + (float) ($destinationHandling->amount ?? 0);
            $handlingTotal += $lineHandlingTotal;

            $originTrucking = 0.0;
            $destinationTrucking = 0.0;

            if ($deliveryType->includes_origin_trucking) {
                $originTrucking = (float) (TruckingTariff::where('area_id', $line['origin_area_id'])
                    ->where('delivery_type_id', $deliveryType->delivery_type_id)
                    ->activeOn($bookingDate)
                    ->value('amount') ?? 0);
            }

            if ($deliveryType->includes_destination_trucking) {
                $destinationTrucking = (float) (TruckingTariff::where('area_id', $line['destination_area_id'])
                    ->where('delivery_type_id', $deliveryType->delivery_type_id)
                    ->activeOn($bookingDate)
                    ->value('amount') ?? 0);
            }

            $lineTruckingTotal = $originTrucking + $destinationTrucking;
            $truckingTotal += $lineTruckingTotal;

            $resolvedLines[] = [
                'container_id' => $line['container_id'],
                'container_class_id' => $line['container_class_id'],
                'container_size_id' => $line['container_size_id'],
                'container_variant_id' => $line['container_variant_id'],
                'quantity' => $quantity,

                'origin_port_id' => $line['origin_port_id'],
                'destination_port_id' => $line['destination_port_id'],
                'origin_area_id' => $line['origin_area_id'],
                'destination_area_id' => $line['destination_area_id'],
                'lane_id' => $lane->lane_id,
                'tariff_rate_id' => $tariffRate->rate_id,
                'delivery_type_id' => $deliveryType->delivery_type_id,

                'frt' => round($frt, 2),
                'discount_type' => $discountType,
                'discount_value' => round($discountValue, 2),
                'frt_after_discount' => round($frtAfterDiscount, 2),
                'line_total' => $lineTotal,

                'port_charges' => [
                    'origin' => $originPortCharges->map(fn ($c) => [
                        'charge_type_id' => $c->charge_type_id,
                        'amount' => (float) $c->amount,
                    ])->values()->all(),
                    'destination' => $destinationPortCharges->map(fn ($c) => [
                        'charge_type_id' => $c->charge_type_id,
                        'amount' => (float) $c->amount,
                    ])->values()->all(),
                    'total' => round($linePortChargesTotal, 2),
                ],
                'handling' => [
                    'origin' => (float) ($originHandling->amount ?? 0),
                    'destination' => (float) ($destinationHandling->amount ?? 0),
                    'total' => round($lineHandlingTotal, 2),
                ],
                'trucking' => [
                    'origin' => round($originTrucking, 2),
                    'destination' => round($destinationTrucking, 2),
                    'total' => round($lineTruckingTotal, 2),
                ],
            ];
        }

        $vatableAmount = $linesTotal + $portChargesTotal + $handlingTotal + $truckingTotal;
        $vatAmount = round($vatableAmount * ((float) $vatRate->rate_percent / 100), 2);
        $grandTotal = round($vatableAmount + $vatAmount, 2);

        return [
            'lines' => $resolvedLines,
            'lines_total' => round($linesTotal, 2),
            'port_charges' => ['total' => round($portChargesTotal, 2)],
            'handling' => ['total' => round($handlingTotal, 2)],
            'trucking' => ['total' => round($truckingTotal, 2)],

            'vat_rate_id' => $vatRate->vat_rate_id,
            'vat_rate_percent' => (float) $vatRate->rate_percent,
            'vat_amount' => $vatAmount,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * @return array{0: ?string, 1: float}
     */
    protected function resolveLineDiscount(array $header, array $line): array
    {
        if (empty($header['client_contract_id'])) {
            return [null, 0.0];
        }

        $contractRate = ClientContractRate::where('contract_id', $header['client_contract_id'])
            ->where('origin_port_id', $line['origin_port_id'])
            ->where('destination_port_id', $line['destination_port_id'])
            ->where('container_id', $line['container_id'])
            ->where('container_class_id', $line['container_class_id'])
            ->where('container_size_id', $line['container_size_id'])
            ->where('container_variant_id', $line['container_variant_id'])
            ->first();

        if (! $contractRate) {
            // Contract exists but no matching line for this route/container -> standard tariff.
            return [null, 0.0];
        }

        // A contract rate can require a minimum quantity before its discount
        // applies (e.g. "10% off, but only for 5+ vans on this lane"). Booking
        // fewer than that falls back to the standard tariff for this line,
        // same as if no contract rate matched at all. Null min_van_qty means
        // no minimum was set - the discount always applies.
        if ($contractRate->min_van_qty !== null && (int) $line['quantity'] < $contractRate->min_van_qty) {
            return [null, 0.0];
        }

        return [$contractRate->discount_type, (float) $contractRate->discount_value];
    }

    protected function applyDiscount(float $frt, ?string $discountType, float $discountValue): float
    {
        if (! $discountType || $discountValue <= 0) {
            return $frt;
        }

        return $discountType === 'percentage'
            ? $frt - ($frt * $discountValue / 100)
            : max(0, $frt - $discountValue);
    }
}
