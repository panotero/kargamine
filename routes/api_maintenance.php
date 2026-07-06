<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\HandlingFeeController;
use App\Http\Controllers\LaneTariffRateController;
use App\Http\Controllers\PortChargeController;
use App\Http\Controllers\TruckingTariffController;
use App\Http\Controllers\VatRateController;


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
