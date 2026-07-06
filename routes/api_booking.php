<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookingController;



// =========================================================
// Booking
// =========================================================

Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::get('/{booking}', [BookingController::class, 'show']);
    Route::post('/quote', [BookingController::class, 'quote']); // live rate preview, no save
    Route::post('/', [BookingController::class, 'store']);
});
