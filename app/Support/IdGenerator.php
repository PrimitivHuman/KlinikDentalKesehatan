<?php

namespace App\Support;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Support\Facades\DB;

/**
 * Generator ID berurutan (mis. DOK-001, AK-1000) yang aman dari:
 *  - Perbandingan string ("AK-999" > "AK-1000"), karena angka dibandingkan secara numerik.
 *  - Penggunaan ulang ID milik record soft-deleted, karena pemanggil memberi query
 *    yang sudah menyertakan withTrashed() bila perlu.
 */
class IdGenerator
{
    /**
     * @param  string                 $prefix  Contoh: 'DOK-'
     * @param  BuilderContract|object $query   Eloquent/Query builder sumber ID
     * @param  string                 $column  Nama kolom ID
     * @param  int                    $pad     Jumlah digit minimal
     */
    public static function next(string $prefix, $query, string $column, int $pad = 3): string
    {
        return DB::transaction(function () use ($prefix, $query, $column, $pad) {
            $max = 0;

            foreach ((clone $query)->lockForUpdate()->pluck($column) as $id) {
                if (preg_match('/(\d+)$/', (string) $id, $m)) {
                    $max = max($max, (int) $m[1]);
                }
            }

            return $prefix . str_pad((string) ($max + 1), $pad, '0', STR_PAD_LEFT);
        });
    }
}
