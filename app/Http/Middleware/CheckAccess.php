<?php

namespace App\Http\Middleware;

use Closure;

class CheckAccess
{

    public function handle($request, Closure $next,$access)
    {
        $akses = in_array($access, auth()->user()->role->getPermissions(), true);
        abort_if($akses===false && !auth()->user()->is_super(),401);

        return $next($request);
    }
}
