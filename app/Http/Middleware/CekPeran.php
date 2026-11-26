<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekPeran
{
    // Validasi akses halaman berdasarkan daftar peran pengguna
    public function handle(Request $request, Closure $next, string ...$daftarPeran): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('galat', 'Silakan masuk terlebih dahulu.');
        }

        if (! in_array(Auth::user()->peran, $daftarPeran, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}

