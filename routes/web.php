<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProposalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth', 'check.status', 'prevent-back-history'])->group(function () {
    Route::get('/app', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/debug_auth', function () {
        $user = auth()->user();
        if ($user) {
            $user->load('office');
        }

        return [
            'isLoggedIn' => auth()->check(),
            'user' => $user,
        ];
    });
    require __DIR__ . '/page.php';
    require __DIR__ . '/mailer.php';


    Route::get('/createpdf/{id}', [ProposalController::class, 'createPdf']);


    Route::resource('users', UserController::class)->middleware('can:isSuperAdmin');

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
    });
});
require __DIR__ . '/auth.php';
