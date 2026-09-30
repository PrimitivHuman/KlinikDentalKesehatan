<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * R7: RoleMiddleware yang diperkuat.
 *
 * Mendukung:
 * - Multi-role: Route::middleware('role:superadmin,admin')
 * - Wildcard role: '*' untuk semua role yang terautentikasi
 * - Logging akses yang ditolak untuk audit trail
 *
 * Tanpa dependency Spatie — menggunakan kolom 'role' di tabel users.
 *
 * Catatan Upgrade: Jika ingin granular permissions per fitur di masa depan,
 * pertimbangkan spatie/laravel-permission via: composer require spatie/laravel-permission
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @param  string                    ...$roles  Role yang diizinkan, bisa lebih dari satu.
     *                                             Contoh: 'superadmin', 'admin', 'operator'
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user     = Auth::user();
        $userRole = $user->role ?? null;

        // Wildcard: jika diizinkan semua role
        if (in_array('*', $roles)) {
            return $next($request);
        }

        // Validasi: kolom role tidak boleh kosong
        if (empty($userRole)) {
            Log::warning('RoleMiddleware: User tanpa role mencoba akses.', [
                'user_id' => $user->id,
                'url'     => $request->fullUrl(),
            ]);
            return redirect('/admin-area')
                ->with('error', 'Akun Anda tidak memiliki role yang valid. Hubungi administrator.');
        }

        // Cek apakah role user ada dalam daftar role yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Akses ditolak — log untuk audit trail
        Log::warning('RoleMiddleware: Akses ditolak.', [
            'user_id'       => $user->id,
            'user_role'     => $userRole,
            'required_roles'=> implode(',', $roles),
            'url'           => $request->fullUrl(),
            'ip'            => $request->ip(),
        ]);

        // Jika request adalah AJAX/API, kembalikan JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Forbidden. Anda tidak memiliki hak akses untuk fitur ini.',
            ], 403);
        }

        return redirect('/admin-area')
            ->with('error', 'Anda tidak memiliki hak akses untuk fitur ini.');
    }
}
