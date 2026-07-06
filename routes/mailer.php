<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailerController;

Route::get('/testmail/{userid}', [MailerController::class, 'test']);
Route::post('/mailer_save', [MailerController::class, 'save'])->name('mailer_save');
Route::post('/mailer/send', [MailerController::class, 'send'])->name('mailer.send');
