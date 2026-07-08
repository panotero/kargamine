<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChargeTypeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DeliveryTypeController;
use App\Http\Controllers\GeneralChargeController;
use App\Http\Controllers\HandlingFeeController;
use App\Http\Controllers\LaneController;
use App\Http\Controllers\LaneTariffRateController;
use App\Http\Controllers\PortChargeController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ServiceableAreaController;
use App\Http\Controllers\TruckingTariffController;
use App\Http\Controllers\VatRateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContainerController;

// =========================================================
// Maintenance: master data
// =========================================================

Route::prefix('ports')->group(function () {
    Route::get('/', [PortController::class, 'index']);
    Route::get('/{port}', [PortController::class, 'show']);
    Route::post('/', [PortController::class, 'store']);
    Route::put('/{port}', [PortController::class, 'update']);
    Route::delete('/{port}', [PortController::class, 'destroy']);
});

Route::prefix('chargeTypes')->group(function () {
    Route::get('/', [ChargeTypeController::class, 'index']);
    Route::get('/{chargeType}', [ChargeTypeController::class, 'show']);
    Route::post('/', [ChargeTypeController::class, 'store']);
    Route::put('/{chargeType}', [ChargeTypeController::class, 'update']);
    Route::delete('/{chargeType}', [ChargeTypeController::class, 'destroy']);
});

Route::prefix('deliveryTypes')->group(function () {
    Route::get('/', [DeliveryTypeController::class, 'index']);
    Route::get('/{deliveryType}', [DeliveryTypeController::class, 'show']);
    Route::post('/', [DeliveryTypeController::class, 'store']);
    Route::put('/{deliveryType}', [DeliveryTypeController::class, 'update']);
    Route::delete('/{deliveryType}', [DeliveryTypeController::class, 'destroy']);
});

Route::prefix('serviceableAreas')->group(function () {
    Route::get('/', [ServiceableAreaController::class, 'index']);
    Route::get('/{serviceableArea}', [ServiceableAreaController::class, 'show']);
    Route::post('/', [ServiceableAreaController::class, 'store']);
    Route::put('/{serviceableArea}', [ServiceableAreaController::class, 'update']);
    Route::delete('/{serviceableArea}', [ServiceableAreaController::class, 'destroy']);
});

Route::prefix('lanes')->group(function () {
    Route::get('/', [LaneController::class, 'index']);
    Route::get('/{lane}', [LaneController::class, 'show']);
    Route::post('/', [LaneController::class, 'store']);
    Route::put('/{lane}', [LaneController::class, 'update']);
    Route::delete('/{lane}', [LaneController::class, 'destroy']);
});

// =========================================================
// Maintenance: versioned rate tables
// =========================================================

Route::prefix('laneTariffRates')->group(function () {
    Route::get('/', [LaneTariffRateController::class, 'index']);
    Route::get('/{laneTariffRate}', [LaneTariffRateController::class, 'show']);
    Route::post('/', [LaneTariffRateController::class, 'store']); // adds a new version, auto-closes the previous one
    Route::put('/{laneTariffRate}', [LaneTariffRateController::class, 'update']); // corrections only (amounts/is_active)
    Route::delete('/{laneTariffRate}', [LaneTariffRateController::class, 'destroy']);
});

Route::prefix('portCharges')->group(function () {
    Route::get('/', [PortChargeController::class, 'index']);
    Route::get('/{portCharge}', [PortChargeController::class, 'show']);
    Route::post('/', [PortChargeController::class, 'store']);
    Route::put('/{portCharge}', [PortChargeController::class, 'update']);
    Route::delete('/{portCharge}', [PortChargeController::class, 'destroy']);
});

// General charges - versioned, applies to every booking, not tied to a
// port or lane (e.g. a flat processing fee). Only charge types created
// with applicable_to = GENERAL are valid here.
Route::prefix('generalCharges')->group(function () {
    Route::get('/', [GeneralChargeController::class, 'index']);
    Route::get('/{generalCharge}', [GeneralChargeController::class, 'show']);
    Route::post('/', [GeneralChargeController::class, 'store']);
    Route::put('/{generalCharge}', [GeneralChargeController::class, 'update']);
    Route::delete('/{generalCharge}', [GeneralChargeController::class, 'destroy']);
});

Route::prefix('handlingFees')->group(function () {
    Route::get('/', [HandlingFeeController::class, 'index']);
    Route::get('/{handlingFee}', [HandlingFeeController::class, 'show']);
    Route::post('/', [HandlingFeeController::class, 'store']);
    Route::put('/{handlingFee}', [HandlingFeeController::class, 'update']);
    Route::delete('/{handlingFee}', [HandlingFeeController::class, 'destroy']);
});

Route::prefix('truckingTariffs')->group(function () {
    Route::get('/', [TruckingTariffController::class, 'index']);
    Route::get('/{truckingTariff}', [TruckingTariffController::class, 'show']);
    Route::post('/', [TruckingTariffController::class, 'store']);
    Route::put('/{truckingTariff}', [TruckingTariffController::class, 'update']);
    Route::delete('/{truckingTariff}', [TruckingTariffController::class, 'destroy']);
});

Route::prefix('vatRates')->group(function () {
    Route::get('/', [VatRateController::class, 'index']);
    Route::get('/{vatRate}', [VatRateController::class, 'show']);
    Route::post('/', [VatRateController::class, 'store']);
    Route::put('/{vatRate}', [VatRateController::class, 'update']);
    Route::delete('/{vatRate}', [VatRateController::class, 'destroy']);
});

// =========================================================
// Booking
// =========================================================

Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::get('/{booking}', [BookingController::class, 'show']);
    Route::post('/quote', [BookingController::class, 'quote']); // live rate preview, no save
    Route::post('/', [BookingController::class, 'store']);
});

// =========================================================
// Contracts
// =========================================================

Route::prefix('contracts')->group(function () {
    Route::get('/', [ContractController::class, 'index']);
    Route::get('/{contract}', [ContractController::class, 'show']);
    Route::post('/', [ContractController::class, 'store']);
    Route::put('/{contract}', [ContractController::class, 'update']);
    Route::delete('/{contract}', [ContractController::class, 'destroy']);
});

// Helper endpoint used by the contract creation form to prefill rate
// lines from the proposal the client already agreed to.
Route::prefix('proposals')->group(function () {
    Route::get('/{proposal}/ratesPrefill', [ContractController::class, 'ratesFromProposal']);
});


// -----------------------------------------------------------------
// Containers (own class/size combinations, priced per lane tariff)
// -----------------------------------------------------------------
Route::prefix('containers')->group(function () {
    Route::get('/', [ContainerController::class, 'index']);
    Route::get('/variants', [ContainerController::class, 'variants']); // must stay above /{container}
    Route::get('/{container}', [ContainerController::class, 'show']);
    Route::post('/', [ContainerController::class, 'store']);
    Route::put('/{container}', [ContainerController::class, 'update']);
    Route::delete('/{container}', [ContainerController::class, 'destroy']);
});

// Simple lookups used by the Container form's dropdowns
Route::get('/containerTypes', fn() => response()->json([
    'success' => true,
    'data' => \DB::table('container_type')->orderBy('type')->get(),
]));
Route::get('/containerClasses', fn() => response()->json([
    'success' => true,
    'data' => \DB::table('container_class')->orderBy('class')->get(),
]));
Route::get('/containerSizes', fn() => response()->json([
    'success' => true,
    'data' => \DB::table('container_size')->orderBy('size')->get(),
]));
