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
        // Registrera våra custom middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'no.club' => \App\Http\Middleware\NoClubMiddleware::class,
            'has.club' => \App\Http\Middleware\HasClubMiddleware::class,
        ]);

        // Lägg till middleware i web-gruppen om det behövs
        $middleware->web(append: [
            // ...
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
