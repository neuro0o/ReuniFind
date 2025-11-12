<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Make sure the user is logged in and has Admin role
        if (!Auth::check() || Auth::user()->userRole !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
