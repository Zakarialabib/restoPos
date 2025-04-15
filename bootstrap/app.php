<?php

declare(strict_types=1);

use App\Http\Middleware\Locale;
use App\Http\Middleware\CheckInstallation;
use App\Http\Middleware\EnsureInstallationComplete;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            Locale::class,
        ]);
        
        // Register the named middleware for roles and permissions
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Add middleware groups for different user types
        $middleware->group('admin', [
            'auth',
            'admin',
            'role:admin|manager|staff',
        ]);

        $middleware->group('customer', [
            'auth',
            'role:customer',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})->create();
