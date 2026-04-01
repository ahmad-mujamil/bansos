<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganisasiNotBlacklisted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $isBlacklisted = (bool) ($user?->userDetail?->organisasi?->is_blacklist ?? false);


        if ($isBlacklisted) {
            if ($request->expectsJson()) {
                abort(403, 'Kelompok/organisasi Anda sedang diblacklist. Akses pengajuan dibatasi.');
            }

            toast()->warning('Akses dibatasi', 'Kelompok/organisasi Anda sedang diblacklist, sehingga tidak dapat mengakses menu Pengajuan.');

            return redirect()
                ->route('blacklist.info')
                ->with('blocked_pengajuan_blacklist', true);
        }

        return $next($request);
    }
}

