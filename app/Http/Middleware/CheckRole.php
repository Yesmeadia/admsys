<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        foreach ($roles as $role) {
            // Check if user has the role
            if ($role === 'admin' && $request->user()->isAdmin()) {
                return $next($request);
            }
            if ($role === 'staff' && $request->user()->isStaff()) {
                return $next($request);
            }
        }

        // If user doesn't have any of the required roles
        abort(403, 'Unauthorized access.');
    }
}
