<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('visited')) {
            $count = (int) \App\Models\SiteSetting::get('visitor_count', 0);
            \App\Models\SiteSetting::set('visitor_count', $count + 1);
            $request->session()->put('visited', true);
        }

        return $next($request);
    }
}
