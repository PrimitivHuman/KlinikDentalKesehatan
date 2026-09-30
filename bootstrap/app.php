<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Laravel 11: bootstrap/app.php
|--------------------------------------------------------------------------
|
| File ini menggantikan app/Http/Kernel.php dan menjadi titik konfigurasi
| utama untuk routing, middleware, dan exception handling.
|
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware kustom
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Kecualikan route dari CSRF verification
        $middleware->validateCsrfTokens(except: [
            // 'stripe/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi exception rendering di sini jika perlu
    })
    ->create();
