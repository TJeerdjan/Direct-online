<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAgency
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access if agency admin OR impersonating (to allow stop-impersonate)
        if (session('user_type') !== 'agency' && !session('impersonating')) {
            abort(403, 'Geen toegang');
        }
        return $next($request);
    }
}
