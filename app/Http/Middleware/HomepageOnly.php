<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HomepageOnly
{
public function handle(Request $request, Closure $next)
{
    $allowed = $request->routeIs('landing*', 'listing.*', 'artikel.*', 'articles.show')
        || $request->is('sitemap.xml', 'robots.txt');

    if ($request->getHost() === config('app.home_domain') && ! $allowed) {
        return redirect()->away(rtrim(config('app.url'), '/').$request->getRequestUri());
    }

    return $next($request);
}
}