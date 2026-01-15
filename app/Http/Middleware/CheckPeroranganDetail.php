<?php

namespace App\Http\Middleware;

use App\Enums\StatusUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPeroranganDetail
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($request->routeIs('perorangan-detail.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        if ($user->is_user() && ($user->status === StatusUser::PERORANGAN || $user->status->value === 7)) {
            if (!$user->relationLoaded('peroranganDetail')) {
                $user->load('peroranganDetail');
            }
            
            if (!$user->peroranganDetail) {
                return redirect()->route('perorangan-detail.create');
            }
        }

        return $next($request);
    }
}

