<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\MailerController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\ApprovalsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ListOfValueController;
use App\Http\Controllers\CrmLeadController;
use App\Http\Controllers\CrmStatusController;
use App\Http\Controllers\CrmActivityController;
use App\Http\Controllers\CrmNoteController;

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
    Route::get('/OfficeDocs', [DocumentController::class, 'OfficeDocs']);







    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::get('/reports/{office_code}', [UserController::class, 'getUsersWithDocs']);
        Route::post('/', [UserController::class, 'store']);
        Route::patch('/save/{id}', [UserController::class, 'save_info']);
        Route::patch('/deactivate/{id}', [UserController::class, 'deactivate']);
        Route::patch('/reactivate/{id}', [UserController::class, 'reactivate']);
        Route::get('/reports/{officename}', [UserController::class, 'reports']);
    });


    Route::post('/send-mail', [MailerController::class, 'send']);


    Route::prefix('nav_menus')->group(function () {
        Route::get('/list', [MenusController::class, 'menulist']);
        Route::post('/', [MenusController::class, 'store']);
        Route::put('/{id}', [MenusController::class, 'update']);
        Route::delete('/{id}', [MenusController::class, 'destroy']);
        Route::post('/swap', [MenusController::class, 'swapMenuOrder']);
    });



    Route::prefix('options')->group(function () {
        Route::get('/', [OptionController::class, 'index']);
        Route::post('/', [OptionController::class, 'store']);
        Route::get('/{id}', [OptionController::class, 'show']);
        Route::put('/{id}', [OptionController::class, 'update']);
        Route::delete('/', [OptionController::class, 'destroy']);

        Route::get('/{optionId}/values', [ListOfValueController::class, 'byOption']);
        Route::post('/{optionId}/values', [ListOfValueController::class, 'storeByOption']);
    });


    // Global LOV routes (if you still want independent access)
    Route::prefix('lov')->group(function () {

        Route::get('/', [ListOfValueController::class, 'index']);
        Route::post('/', [ListOfValueController::class, 'store']);
        Route::get('/{id}', [ListOfValueController::class, 'show']);
        Route::put('/{id}', [ListOfValueController::class, 'update']);
        Route::delete('/', [ListOfValueController::class, 'destroy']);
    });

    Route::prefix('companies')->group(function () {

        Route::get('/', [CompanyController::class, 'index']);
        Route::post('/', [CompanyController::class, 'store']);
        Route::get('/{id}', [CompanyController::class, 'show']);
        Route::put('/{id}', [CompanyController::class, 'update']);
        Route::delete('/{id}', [CompanyController::class, 'destroy']);
    });


    Route::prefix('crm')->group(function () {

        // LEADS (create full lead package)
        Route::post('/leads', [CrmLeadController::class, 'store']);
        Route::get('/leads', [CrmLeadController::class, 'index']);
        Route::get('/leads/{uuid}', [CrmLeadController::class, 'show']);
        Route::put('/leads/{uuid}', [CrmLeadController::class, 'update']);
        Route::delete('/leads/{uuid}', [CrmLeadController::class, 'destroy']);

        // STATUS CRUD
        Route::get('/getCrmStatus', [CrmStatusController::class, 'index']);

        // ACTIVITIES CRUD
        Route::apiResource('activities', CrmActivityController::class);

        // TEST BY PAGE FETCHING
        Route::get('/leads/datatables', [CrmLeadController::class, 'datatable']);

        Route::post('/note', [CrmNoteController::class, 'store']);
        Route::post('/activity', [CrmActivityController::class, 'store']);
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
