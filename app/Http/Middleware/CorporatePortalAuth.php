<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorporatePortalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated (has userid in session)
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login')->with('info', 'Please login to access the Corporate Portal');
        }

        return $next($request);
    }
}
