<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Memeriksa role pengguna saat ini (misal: superadmin, admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles  Daftar role dipisahkan koma (contoh: superadmin,admin)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRole = $user->role ?? 'admin';

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return redirect('/admin-area')->with('error', 'Anda tidak memiliki hak akses untuk fitur ini.');
    }
}
