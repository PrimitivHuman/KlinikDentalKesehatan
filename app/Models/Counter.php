<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $table = 'counter';

    public $incrementing = false;
    public $timestamps   = false;

    protected $fillable = ['date', 'ip', 'ip_addr', 'count'];

    /**
     * #7 Fix: Mengambil data kunjungan seminggu dalam 1 query efisien.
     * Mengembalikan array: [collection_seminggu, jml_senin, selasa, rabu, kamis, jumat, sabtu, minggu]
     */
    public static function getCounterData(): array
    {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end   = date("Y-m-d", strtotime('sunday this week'));

        $records = Counter::whereBetween('date', [$start, $end])->get();

        $dailyCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $day = date("Y-m-d", strtotime("monday this week +{$i} days"));
            $dailyCounts[$day] = 0;
        }

        foreach ($records as $r) {
            $d = substr((string) $r->date, 0, 10);
            if (isset($dailyCounts[$d])) {
                $dailyCounts[$d] += (int) ($r->count ?? 1);
            }
        }

        return array_merge([$records], array_values($dailyCounts));
    }
}
