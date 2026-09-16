<?php

use App\Http\Controllers\ProposalSigningController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Proposal signing routes (public, signed-URL gated)
|--------------------------------------------------------------------------
|
| Reached by a client's authorized signatory via the link emailed once a
| proposal is approved. No `auth` middleware - the recipient is external to
| the app - each route instead requires a valid Laravel signed URL.
|
*/

Route::prefix('proposal-sign')->middleware('signed')->group(function () {
    Route::get('/{proposal:uuid}', [ProposalSigningController::class, 'show'])->name('proposal.sign.show');
    Route::get('/{proposal:uuid}/pdf', [ProposalSigningController::class, 'downloadPdf'])->name('proposal.sign.pdf');
    Route::post('/{proposal:uuid}/upload', [ProposalSigningController::class, 'upload'])->name('proposal.sign.upload');
});
