<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserActive
{
    /**
     * Routes that user dengan is_active=false boleh akses.
     */
    protected array $allowedForInactiveUser = [
        'home',
        'logout',
        'profile.index',
        'profile.update',
        'security.index',
        'password.update',
        'user-detail.create',
        'user-detail.store',
        'user-detail.update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Hanya berlaku untuk role user
        if (!$user->is_user()) {
            return $next($request);
        }

        // User aktif -> boleh akses semua
        if ($user->is_active) {
            return $next($request);
        }

        // User belum aktif: hanya boleh akses route yang diizinkan
        if ($request->routeIs($this->allowedForInactiveUser)) {
            return $next($request);
        }

        return redirect()
            ->route('home')
            ->with('warning', 'Akun Anda belum diverifikasi. Halaman tersebut tidak dapat diakses.');
    }
}
