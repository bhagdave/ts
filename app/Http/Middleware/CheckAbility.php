<?php

namespace App\Http\Middleware;

use Closure;
use App\User; 
use Bouncer;

class CheckAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $ability)
    {
       
        if (!User::current()->can($ability)) {
            abort(403);
        }

        return $next($request);
    }
}
