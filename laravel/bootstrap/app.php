<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(
            except: [
                'contracts/*/terminate',
                'players/*/negotiate'
            ]
        );

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'has.club' => \App\Http\Middleware\HasClubMiddleware::class,
            'no.club' => \App\Http\Middleware\NoClubMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
