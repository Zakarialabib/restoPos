<?php

declare(strict_types=1);

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // SetLocale
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        $middleware->append(SetLocale::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    })->create();
