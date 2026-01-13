<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{

    public function handle($request, Closure $next,$roles)
    {

        $array_roles = array($roles);

        if(!is_array($array_roles) && !in_array(auth()->user()->role->value, $array_roles, true)) {
            abort(401);
        }

        return $next($request);
    }
}
