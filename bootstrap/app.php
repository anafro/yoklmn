<?php

use App\Http\Middleware\ForbidAuthenticated;
use App\Http\Middleware\ForbidGuests;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            'auth.forbidden' => ForbidAuthenticated::class,
            'guest.forbidden' => ForbidGuests::class,
        ]);

        $middleware->redirectGuestsTo(fn() => route('auth.login'));
        $middleware->redirectUsersTo(fn() => route('menu'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
