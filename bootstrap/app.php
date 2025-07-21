<?php

declare(strict_types=1);

use App\Http\Middleware\Locale;
use App\Models\Admin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        // admin: __DIR__ . '/../routes/admin.php',
        // auth: __DIR__ . '/../routes/auth.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            Locale::class,
        ]);


        $middleware->alias([
            'admin' => App\Http\Middleware\AdminMiddleware::class,
            'role' => Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->group('admin', [
            'web',
            'auth:admin',
            'role:admin|manager|staff',
        ]);

        $middleware->group('customer', [
            'auth:web',
            'role:customer',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {})
    ->create();
