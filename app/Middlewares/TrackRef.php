<?php

namespace Jiannius\Scaffold\Middlewares;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class TrackRef
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('get') && $duration = config('scaffold.web.track_ref.duration')) {
            if (!$this->isRouteExcluded($request)) {
                $ref = request()->query('ref');
                $cookie = $this->getCookie();
    
                // ref is trackable
                if ($ref && $this->isTrackable($ref)) {
                    if ($ref !== $cookie) Cookie::queue('_ref', $ref, ($duration * 24 * 60));
                }
                // got ref cookie, append the ref={cookie} to url
                else if ($cookie) {
                    return redirect($request->fullUrlWithQuery(
                        array_merge($request->query(), ['ref' => $cookie])
                    ));
                }
                // if visit to register page with no ref and no ref cookie, 
                // redirect to avoid bot signup
                else if (!$ref && $request->route()->getName() === 'register') {
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }

    /**
     * Check current route is excluded from ref tracking
     * 
     * @return boolean
     */
    public function isRouteExcluded($request)
    {
        $list = config('scaffold.web.track_ref.exclude_routes');

        return collect($list)->contains(function($name) {
            return $name === request()->route()->getName()
                || $name === request()->path()
                || request()->is($name);
        });
    }

    /**
     * Get cookie
     * 
     * @return string
     */
    public function getCookie()
    {
        $value = request()->cookie('_ref');

        if (!$value) return null;

        $decrypt = explode('|', decrypt($value, false));
        $cookie = end($decrypt);

        return $cookie;
    }

    /**
     * Check ref is trackable
     * 
     * @param string $ref
     * @return boolean
     */
    public function isTrackable($ref)
    {
        return !Str::startsWith($ref, 'page-')
            && !Str::startsWith($ref, 'navbar-');
    }
}