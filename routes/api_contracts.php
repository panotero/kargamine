<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ContractController;

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
    Route::get('/{proposal}/rates-prefill', [ContractController::class, 'ratesFromProposal']);
});
