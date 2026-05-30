<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/test.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Fix RedirectIfAuthenticated to return proper Response
        $middleware->redirectGuestsTo(fn () => route('login'));
        
        // Fix guest middleware redirect - must return route, not redirect()
        $middleware->redirectUsersTo(function () {
            $user = Auth::user();
            if ($user && $user->role === 'admin') {
                return '/admin/dashboard';
            }
            return '/user/tempat-makan';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
