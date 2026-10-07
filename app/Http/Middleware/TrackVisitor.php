<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Counter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * #7 Fix: Middleware untuk mencatat statistik kunjungan pengunjung publik ke tabel counter.
 */
class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya hitung pengunjung publik non-admin yang menggunakan metode GET
        if (!Auth::check() && $request->isMethod('GET')) {
            try {
                $today = date('Y-m-d');
                $ip    = $request->ip() ?: 'UNKNOWN';

                $counter = Counter::where('date', $today)
                    ->where(function ($q) use ($ip) {
                        $q->where('ip', $ip)->orWhere('ip_addr', $ip);
                    })
                    ->first();

                if ($counter) {
                    $counter->count = ((int) ($counter->count ?? 1)) + 1;
                    $counter->save();
                } else {
                    Counter::create([
                        'date'    => $today,
                        'ip'      => $ip,
                        'ip_addr' => $ip,
                        'count'   => 1,
                    ]);
                }
            } catch (\Throwable $e) {
                // Jangan gagalkan request jika logging visitor gagal
            }
        }

        return $next($request);
    }
}
