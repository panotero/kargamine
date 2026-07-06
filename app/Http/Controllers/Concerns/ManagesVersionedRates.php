<?php

namespace App\Http\Controllers\Concerns;

use Carbon\Carbon;

/**
 * Shared helper for maintenance controllers over versioned rate tables
 * (lane_tariff_rates, port_charges, handling_fees, trucking_tariffs, vat_rates).
 *
 * When a new rate is added, the previously active version within the same
 * scope (e.g. same lane_id, or same port_id + charge_type_id) is
 * automatically closed off (end_date set, is_active = false) so the
 * maintenance page never has two conflicting active rates at once.
 */
trait ManagesVersionedRates
{
    protected function closePreviousVersion(string $modelClass, array $scope, string $newEffectiveDate): void
    {
        $modelClass::where($scope)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'end_date' => Carbon::parse($newEffectiveDate)->subDay()->toDateString(),
            ]);
    }
}
