<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{

    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();
        if ($user && $user->is_super())
            return $next($request);

        if (!$user || !$user->role || !in_array($user->role->value, $roles, true)) {
            abort(401);
        }

        return $next($request);
    }
}
