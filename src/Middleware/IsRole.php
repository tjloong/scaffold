<?php

namespace Jiannius\Scaffold\Middleware;

use Closure;

class IsRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $rolename)
    {
        if ($request->user() && $request->user()->is($rolename)) return $next($request);

        abort(403);
    }
}
