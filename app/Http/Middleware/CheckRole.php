<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Kita hapus '...$roles' dari sini untuk menghindari error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Cek apakah user login
        if (!Auth::check()) {
            return redirect('login');
        }

        //Ambil role user yang sedang login
        $userRole = $request->user()->role;

        //Ambil semua argumen peran (misal: 'admin' atau 'user', 'vendor')
        $roles = array_slice(func_get_args(), 2);

        // 4. Loop semua peran yang diizinkan
        foreach ($roles as $role) {
            if ($userRole == $role) {
                // Jika role-nya cocok, izinkan masuk
                return $next($request);
            }
        }

        return redirect('/dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
    }
}