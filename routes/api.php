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
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\LovController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ClientMasterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Organized by feature/module. Middleware groups are used where needed.
| Each resource is grouped using Route::prefix for clarity.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/load_menu', [MenusController::class, 'index']);
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
    });


    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead']);
    Route::post('/documents/route', [RoutingController::class, 'routeDocument']);
    Route::get('/notifications/stream', [NotificationController::class, 'stream']);


    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/settings', [UserController::class, 'getUserSettings']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::patch('/save/{id}', [UserController::class, 'save_info']);
        Route::patch('/deactivate/{id}', [UserController::class, 'deactivate']);
        Route::patch('/reactivate/{id}', [UserController::class, 'reactivate']);
    });


    Route::prefix('contracts')->name('contracts.')->group(function () {

        Route::get('/', [ContractController::class, 'index'])
            ->name('index');

        Route::get('/{contract}', [ContractController::class, 'show'])
            ->name('show');
        Route::post('/', [ContractController::class, 'store'])
            ->name('store');

        Route::put('/{contract}', [ContractController::class, 'update'])
            ->name('update');

        // Delete contract
        Route::delete('/{contract}', [ContractController::class, 'destroy'])
            ->name('destroy');

        // Cancel contract
        Route::patch('/{contract}/cancel', [ContractController::class, 'cancel'])
            ->name('cancel');
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


    Route::prefix('proposal')->group(function () {
        Route::get('/', [ProposalController::class, 'index']);
        Route::post('/', [ProposalController::class, 'store']);
        Route::get('/{proposalcode}', [ProposalController::class, 'getByCode']);
        Route::post('/approvals', [ProposalController::class, 'processApprovals']);
    });
    Route::prefix('listofval')->group(function () {
        Route::get('/route', [LovController::class, 'route']);
        Route::get('/service', [LovController::class, 'service']);
        Route::get('/vanclass', [LovController::class, 'vanclass']);
        Route::get('/vantype', [LovController::class, 'vantype']);
        Route::get('/vansize', [LovController::class, 'vansize']);
    });


    Route::get('/roles', fn() => DB::table('setting_role')->get());

    Route::post('/test-api', function (Request $request) {
        Log::info('Test API triggered', $request->all());
        return response()->json([
            'success' => true,
            'message' => 'API successfully triggered!',
        ]);
    });
    Route::prefix('clientMasters')->group(function () {
        Route::get('/', [ClientMasterController::class, 'index']);
        Route::get('/{uuid}', [ClientMasterController::class, 'show']);
        Route::post('/stage1', [ClientMasterController::class, 'saveStage1']);
        Route::post('/{uuid}/stage2', [ClientMasterController::class, 'saveStage2']);
        Route::post('/{uuid}/stage3', [ClientMasterController::class, 'saveStage3']);
        Route::delete('/{uuid}', [ClientMasterController::class, 'destroy']);
    });

    require __DIR__ . '/api_maintenance.php';
});
