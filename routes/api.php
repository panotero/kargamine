<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\MailerController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\UserConfigController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\ApprovalsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Organized by feature/module. Middleware groups are used where needed.
| Each resource is grouped using Route::prefix for clarity.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
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
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/user_info', function () {

        $user = auth()->user();
        if ($user) {
            $user->load('office', 'userConfig');
        }

        return [
            'isLoggedIn' => auth()->check(),
            'user' => $user,
        ];
    });

    Route::get('/load_menu', [MenusController::class, 'index']);
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
    });

    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead']);
    Route::post('/documents/route', [RoutingController::class, 'routeDocument']);
    Route::prefix('approvals')->group(function () {

        Route::get('/', [ApprovalsController::class, 'getMyApprovals']);

        Route::post('/{approval_id}/action', [ApprovalsController::class, 'handleApprovalAction']);
    });
    Route::get('/notifications/stream', [NotificationController::class, 'stream']);
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::get('/{ControlNumber}', [DocumentController::class, 'show']);
        Route::post('/', [DocumentController::class, 'store']);
        Route::post('/revise', [DocumentController::class, 'revise']);
        Route::post('/confirm', [DocumentController::class, 'confirm']);
        Route::patch('/{id}', [DocumentController::class, 'update']);
        Route::delete('/{id}', [DocumentController::class, 'destroy']);
    });


    Route::post('/activities', [ActivityController::class, 'store'])
        ->name('api.activities.store');

    Route::prefix('offices')->group(function () {
        Route::get('/', [OfficeController::class, 'index']);
        Route::post('/', [OfficeController::class, 'store']);
        Route::delete('/{id}', [OfficeController::class, 'destroy']);
    });


    Route::prefix('userconfigs')->group(function () {
        Route::get('/', [UserConfigController::class, 'index']);
        Route::post('/', [UserConfigController::class, 'store']);
        Route::delete('/{id}', [UserConfigController::class, 'destroy']);
    });

    Route::prefix('documenttypes')->group(function () {
        Route::get('/', [DocumentTypeController::class, 'index']);
        Route::post('/', [DocumentTypeController::class, 'store']);
        Route::get('/{id}', [DocumentTypeController::class, 'show']);
        Route::patch('/{id}', [DocumentTypeController::class, 'update']);
        Route::delete('/{id}', [DocumentTypeController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::patch('/save/{id}', [UserController::class, 'save_info']);
        Route::patch('/deactivate/{id}', [UserController::class, 'deactivate']);
        Route::patch('/reactivate/{id}', [UserController::class, 'reactivate']);
        Route::get('/reports/{officename}', [UserController::class, 'reports']);
    });


    Route::prefix('activities')->group(function () {
        Route::get('/byOffice/{officeName}', [ActivityController::class, 'getActivitiesByOffice']);
        Route::get('/', [ActivityController::class, 'index']);
        Route::get('/{id}', [ActivityController::class, 'show']);
        Route::post('/', [ActivityController::class, 'store']);
        Route::delete('/{id}', [ActivityController::class, 'destroy']);
    });

    Route::post('/send-mail', [MailerController::class, 'send']);

    Route::prefix('nav_menus')->group(function () {
        Route::get('/list', [MenusController::class, 'menulist']);
        Route::post('/', [MenusController::class, 'store']);
        Route::put('/{id}', [MenusController::class, 'update']);
        Route::delete('/{id}', [MenusController::class, 'destroy']);
        Route::post('/swap', [MenusController::class, 'swapMenuOrder']);
    });


    Route::prefix('listings')->group(function () {
        Route::get('/', [ListingController::class, 'index']);
        Route::get('/{id}', [ListingController::class, 'show']);
        Route::post('/', [ListingController::class, 'store'])->name('listings.store');
        Route::put('/{id}', [ListingController::class, 'update']);
        Route::delete('/{id}', [ListingController::class, 'destroy'])->name('listings.destroy');
    });

    Route::get('/roles', fn() => DB::table('setting_role')->get());

    Route::post('/test-api', function (Request $request) {
        Log::info('Test API triggered', $request->all());
        return response()->json([
            'success' => true,
            'message' => 'API successfully triggered!',
        ]);
    });
});
