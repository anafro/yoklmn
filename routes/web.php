<?php

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MenuPageController;
use App\Http\Controllers\LoginPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\TrainingPageController;
use App\Http\Controllers\UserExistsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', MenuPageController::class)->name('menu');
    Route::get('/тренировка', TrainingPageController::class)->name('menu');
});

Route::as('auth.')->middleware("guest")->group(function () {
    Route::get('/войти', LoginPageController::class)->name('login');
});

Route::prefix('api/v1')->as('v1.')->group(function () {
    Route::prefix('auth')
         ->as('auth.')
         ->group(function () {
             Route::post('login', LoginController::class)->name('login')->middleware('auth.forbidden');
             Route::post('signup', SignupController::class)->name('signup')->middleware('auth.forbidden');
             Route::post('logout', LogoutController::class)->name('logout')->middleware('guest.forbidden');
         });

    Route::prefix('users')->as('users.')->group(function () {
        Route::get('exists', UserExistsController::class)->name('exists');
    });
});
