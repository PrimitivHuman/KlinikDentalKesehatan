<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * Menghapus cache halaman beranda otomatis saat data model berubah,
 * sehingga perubahan dari panel admin langsung tampil di website publik.
 */
trait ClearsHomeCache
{
    /** Daftar key cache yang dipakai MainController@index. */
    public const HOME_CACHE_KEYS = [
        'home_galeri',
        'home_tentang',
        'home_dokter',
        'home_layanans',
        'home_beritas',
        'home_kegiatans',
    ];

    public static function flushHomeCache(): void
    {
        foreach (self::HOME_CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }

    public static function bootClearsHomeCache(): void
    {
        $flush = static fn () => self::flushHomeCache();

        static::saved($flush);
        static::deleted($flush);

        if (method_exists(static::class, 'restored')) {
            static::restored($flush);
        }
    }
}
