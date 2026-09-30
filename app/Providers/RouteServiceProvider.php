<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * L11: RouteServiceProvider disederhanakan.
 * Routing sudah didaftarkan di bootstrap/app.php menggunakan withRouting().
 * File ini hanya mempertahankan konstanta HOME untuk kompatibilitas.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Path redirect setelah login berhasil.
     */
    public const HOME = '/admin-area';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
