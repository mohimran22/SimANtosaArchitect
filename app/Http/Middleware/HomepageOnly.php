<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HomepageOnly
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->getHost() === config('app.home_domain')
            && ! $request->routeIs('landing*')) {
            return redirect()->away(rtrim(config('app.url'), '/').$request->getRequestUri());
        }

        return $next($request);
    }
}