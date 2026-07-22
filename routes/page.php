<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/page_dashboard', [PageController::class, 'page_dashboard']);
Route::get('/page_usermanagement', [PageController::class, 'page_UserManagement']);
Route::get('/page_menus', [PageController::class, 'page_Menus']);
Route::get('/page_users', [PageController::class, 'page_Users']);
Route::get('/page_settings', [PageController::class, 'page_settings']);
Route::get('/page_maintenance', [PageController::class, 'page_maintenance']);
Route::get('/page_contracts', [PageController::class, 'page_contracts']);
Route::get('/page_reports', [PageController::class, 'page_reports']);
Route::get('/page_crm', [PageController::class, 'page_crm']);
Route::get('/page_proposals', [PageController::class, 'page_proposals']);
Route::get('/page_clientMasters', [PageController::class, 'page_clientMasters']);
Route::get('/page_clientMasterForm', [PageController::class, 'page_clientMasterForm']);
Route::get('/page_crmLeadForm', [PageController::class, 'page_crmLeadForm']);

Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::get('/settings', [PageController::class, 'settings'])->name('settings');

Route::get('/page_mailer', [PageController::class, 'page_Mailer']);
Route::get('/page_theme', [PageController::class, 'page_Themes']);
