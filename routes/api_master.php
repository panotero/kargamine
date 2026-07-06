<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChargeTypeController;
use App\Http\Controllers\DeliveryTypeController;
use App\Http\Controllers\LaneController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ServiceableAreaController;

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
