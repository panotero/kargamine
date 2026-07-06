<?php

// Add these to your existing routes/api.php (grouped for readability -
// merge into your existing middleware groups as needed, e.g. auth:sanctum).

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChargeTypeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DeliveryTypeController;
use App\Http\Controllers\HandlingFeeController;
use App\Http\Controllers\LaneController;
use App\Http\Controllers\LaneTariffRateController;
use App\Http\Controllers\PortChargeController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ServiceableAreaController;
use App\Http\Controllers\TruckingTariffController;
use App\Http\Controllers\VatRateController;
use Illuminate\Support\Facades\Route;

// --- Booking ---
Route::get('bookings', [BookingController::class, 'index']);
Route::get('bookings/{booking}', [BookingController::class, 'show']);
Route::post('bookings/quote', [BookingController::class, 'quote']); // live preview, no save
Route::post('bookings', [BookingController::class, 'store']);

// --- Maintenance: master data ---
Route::apiResource('ports', PortController::class);
Route::apiResource('serviceableAreas', ServiceableAreaController::class);
Route::apiResource('deliveryTypes', DeliveryTypeController::class);
Route::apiResource('chargeTypes', ChargeTypeController::class);
Route::apiResource('lanes', LaneController::class);

// --- Maintenance: versioned rates ---
Route::apiResource('laneTariffRates', LaneTariffRateController::class);
Route::apiResource('portCharges', PortChargeController::class);
Route::apiResource('handlingFees', HandlingFeeController::class);
Route::apiResource('truckingTariffs', TruckingTariffController::class);
Route::apiResource('vatRates', VatRateController::class);

// --- Contracts ---
Route::apiResource('contracts', ContractController::class)->except(['destroy']);
Route::delete('contracts/{contract}', [ContractController::class, 'destroy']);
Route::get('proposals/{proposal}/rates-prefill', [ContractController::class, 'ratesFromProposal']);
