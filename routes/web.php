<?php

use App\Http\Controllers\CheckWordController;
use App\Http\Controllers\CreateRoomController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MenuPageController;
use App\Http\Controllers\LoginPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RandomTokenController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\TrainingPageController;
use App\Http\Controllers\UserExistsController;
use App\Http\Controllers\RoomPageController;
use Anafro\Biosphere\Facades\Biosphere;
use Illuminate\Support\Facades\Route;

Biosphere::routes();

Route::as('auth.')->middleware("guest")->group(function () {
    Route::get('/войти', LoginPageController::class)->name('login');
});

Route::post('/broadcasting/auth/debug', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user()?->id,
        'session_id' => session()->getId(),
        'guard' => config('auth.defaults.guard'),
    ]);
})->middleware(['web', 'auth']);

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

    Route::middleware('guest.forbidden')
        ->group(function () {
            Route::post('create-room', CreateRoomController::class)->name('create-room');
            Route::post('random-token', RandomTokenController::class)->name('random-token');
            Route::post('check-word', CheckWordController::class)->name('check-word');
        });
});

Route::middleware('auth')->group(function () {
    Route::get('/', MenuPageController::class)->name('menu');
    Route::get('/тренировка', TrainingPageController::class)->name('menu');
    Route::get('/{code}', RoomPageController::class)
        ->where('code', config('rooms.code.regex'))
        ->name('room');
});
