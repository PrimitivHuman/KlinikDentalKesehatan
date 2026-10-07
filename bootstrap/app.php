<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Laravel 11: bootstrap/app.php
|--------------------------------------------------------------------------
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Percayai semua reverse proxy untuk tunneling (Cloudflare, Ngrok, Load Balancer)
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'role'          => \App\Http\Middleware\RoleMiddleware::class,
            'track.visitor' => \App\Http\Middleware\TrackVisitor::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // #16 Fix: Tangani DecryptException secara anggun (404/redirect), bukan 500
        $exceptions->render(function (DecryptException $e, Request $request) {
            if ($request->is('admin-area*')) {
                return redirect()->back()->with('error', 'Parameter ID yang diakses tidak valid atau telah dimodifikasi.');
            }
            abort(404, 'Halaman atau data yang Anda cari tidak ditemukan.');
        });
    })
    ->create();
