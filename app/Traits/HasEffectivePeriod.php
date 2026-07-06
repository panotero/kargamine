<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Shared query scope for any rate table that follows the
 * effective_date / end_date / is_active versioning pattern
 * (lane_tariff_rates, port_charges, handling_fees, trucking_tariffs, vat_rates).
 */
trait HasEffectivePeriod
{
    /**
     * Scope a query to only the version that is active on a given date.
     * Defaults to "today" when no date is passed.
     */
    public function scopeActiveOn(Builder $query, $date = null): Builder
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();

        return $query->where('is_active', true)
            ->whereDate('effective_date', '<=', $date)
            ->where(function (Builder $q) use ($date) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $date);
            });
    }
}
