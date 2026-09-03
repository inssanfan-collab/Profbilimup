<?php

use App\Http\Middleware\EnsureUserIsStaff;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        then: function () {
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(__DIR__.'/../routes/admin.php');

            Route::middleware(['web', 'auth'])
                ->prefix('listener')
                ->name('listener.')
                ->group(__DIR__.'/../routes/listener.php');
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsStaff::class,
        ]);

        $middleware->web(append: [SetLocale::class]);

        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
