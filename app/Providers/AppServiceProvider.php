<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * L11: Semua konfigurasi global aplikasi dipusatkan di sini.
     */
    public function boot(): void
    {
        // Aktifkan strict mode Eloquent di non-production
        // Mencegah lazy loading, mass assignment tanpa fillable, dll.
        Model::shouldBeStrict(! app()->isProduction());

        // Force HTTPS di production
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
