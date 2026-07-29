<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingDispatchController;
use App\Http\Controllers\BookingEirController;
use App\Http\Controllers\BookingGateScanController;
use App\Http\Controllers\BookingVoyageController;
use App\Http\Controllers\CargoBuildUpController;
use App\Http\Controllers\ChargeTypeController;
use App\Http\Controllers\ContainerAssetController;
use App\Http\Controllers\ContainerClassController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\ContainerSizeController;
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
use App\Http\Controllers\VesselVoyageController;
use Illuminate\Support\Facades\Route;

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

// SOP Step 10 (Voyage Plan) - master data
Route::prefix('vesselVoyages')->group(function () {
    Route::get('/', [VesselVoyageController::class, 'index']);
    Route::get('/{vesselVoyage}/loadlist', [VesselVoyageController::class, 'loadlist']) // must stay above /{vesselVoyage}
        ->middleware('permission:booking.generate-loadlist');
    Route::get('/{vesselVoyage}', [VesselVoyageController::class, 'show']);
    Route::post('/', [VesselVoyageController::class, 'store']);
    Route::put('/{vesselVoyage}', [VesselVoyageController::class, 'update']);
    Route::delete('/{vesselVoyage}', [VesselVoyageController::class, 'destroy']);
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
    Route::post('/quote', [BookingController::class, 'quote']); // live rate preview, no save - must stay above /{booking}
    Route::get('/{booking}', [BookingController::class, 'show']);
    Route::post('/', [BookingController::class, 'store'])->middleware('permission:booking.create');
    Route::put('/{booking}', [BookingController::class, 'update'])->middleware('permission:booking.create');
    Route::post('/{booking}/confirm', [BookingController::class, 'confirm'])->middleware('permission:booking.confirm');
    Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->middleware('permission:booking.cancel');
    Route::post('/{booking}/mark-in-transit', [BookingController::class, 'markInTransit'])->middleware('permission:booking.advance-status');
    Route::post('/{booking}/mark-delivered', [BookingController::class, 'markDelivered'])->middleware('permission:booking.advance-status');
    Route::post('/{booking}/mark-completed', [BookingController::class, 'markCompleted'])->middleware('permission:booking.advance-status');
    // Downloading an already-issued document is a read action, not gated
    // behind the same permission that created it.
    Route::get('/{booking}/bol', [BookingController::class, 'downloadBol']);
});

// Phase 2: Cargo Build-Up status board (SOP Step 2) - read-only, no
// permission gate beyond being authenticated, same as the bookings list above.
Route::prefix('cargo-build-up')->group(function () {
    Route::get('/', [CargoBuildUpController::class, 'index']);
    Route::get('/bookings', [CargoBuildUpController::class, 'bookings']); // ?bucket= - must stay above nothing else needs it
});

// Phase 3: ATW/CAN issuance + CV assignment (SOP Steps 3-4)
Route::post('/booking-lines/{bookingLine}/dispatch-document', [BookingDispatchController::class, 'generate'])
    ->middleware('permission:booking.generate-dispatch-document');
Route::put('/booking-container-units/{bookingContainerUnit}/cv-assignment', [BookingDispatchController::class, 'updateCvAssignment'])
    ->middleware('permission:booking.assign-cv');

// Phase 4: Gate Pass scan-confirmation (SOP Steps 6-7) - Pier Check-In page
Route::prefix('gate-scan')->middleware('permission:booking.gate-scan')->group(function () {
    Route::get('/pending', [BookingGateScanController::class, 'pending']); // must stay above nothing else needs it
    Route::post('/', [BookingGateScanController::class, 'scan']);
});

// Phase 5: EIR Out/In (SOP Steps 5 & 9)
Route::middleware('permission:booking.issue-eir')->group(function () {
    Route::post('/booking-container-units/eir-upload', [BookingEirController::class, 'uploadFile']); // must stay above nothing else needs it
    Route::post('/booking-container-units/{bookingContainerUnit}/eir-out', [BookingEirController::class, 'issueOut']);
    Route::post('/booking-container-units/{bookingContainerUnit}/eir-in', [BookingEirController::class, 'issueIn']);
});

// Phase 6: Vessel Voyage assignment + Shut Out (SOP Steps 10-11)
Route::middleware('permission:booking.assign-voyage')->group(function () {
    Route::post('/booking-container-units/{bookingContainerUnit}/assign-voyage', [BookingVoyageController::class, 'assign']);
    Route::post('/booking-container-units/{bookingContainerUnit}/shut-out', [BookingVoyageController::class, 'shutOut']);
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

// -----------------------------------------------------------------
// Container Inventory (physical fleet - the actual boxes, not the
// class/type/size catalog above). Read + availability lookup stay
// behind plain auth; the management actions below are additionally
// gated by nav.access, which checks nav_menus.allowed_roles for
// /page_container_inventory (see EnsureNavMenuAccess) rather than a
// separately-maintained role list.
// -----------------------------------------------------------------
Route::prefix('container-assets')->group(function () {
    Route::get('/available', [ContainerAssetController::class, 'available']); // must stay above /{containerAsset}
    Route::get('/', [ContainerAssetController::class, 'index']);
    Route::get('/{containerAsset}', [ContainerAssetController::class, 'show']);
    Route::post('/reserve', [ContainerAssetController::class, 'reserve']);
    Route::post('/release', [ContainerAssetController::class, 'release']);

    Route::post('/', [ContainerAssetController::class, 'store'])
        ->middleware('nav.access:/page_container_inventory');
    Route::put('/{containerAsset}', [ContainerAssetController::class, 'update'])
        ->middleware('nav.access:/page_container_inventory');
    Route::post('/{containerAsset}/mark-under-repair', [ContainerAssetController::class, 'markUnderRepair'])
        ->middleware('nav.access:/page_container_inventory');
    Route::post('/{containerAsset}/mark-available', [ContainerAssetController::class, 'markAvailable'])
        ->middleware('nav.access:/page_container_inventory');
    Route::post('/{containerAsset}/mark-out-of-service', [ContainerAssetController::class, 'markOutOfService'])
        ->middleware('nav.access:/page_container_inventory');
    Route::post('/{containerAsset}/relocate', [ContainerAssetController::class, 'relocate'])
        ->middleware('nav.access:/page_container_inventory');
});

// Simple lookups used by the Container form's dropdowns
Route::get('/containerTypes', fn () => response()->json([
    'success' => true,
    'data' => \DB::table('container_type')->orderBy('type')->get(),
]));
Route::prefix('containerClasses')->group(function () {
    Route::get('/', [ContainerClassController::class, 'index']);
    Route::get('/{containerClass}', [ContainerClassController::class, 'show']);
    Route::post('/', [ContainerClassController::class, 'store']);
    Route::put('/{containerClass}', [ContainerClassController::class, 'update']);
    Route::delete('/{containerClass}', [ContainerClassController::class, 'destroy']);
});

Route::prefix('containerSizes')->group(function () {
    Route::get('/', [ContainerSizeController::class, 'index']);
    Route::get('/{containerSize}', [ContainerSizeController::class, 'show']);
    Route::post('/', [ContainerSizeController::class, 'store']);
    Route::put('/{containerSize}', [ContainerSizeController::class, 'update']);
    Route::delete('/{containerSize}', [ContainerSizeController::class, 'destroy']);
});
