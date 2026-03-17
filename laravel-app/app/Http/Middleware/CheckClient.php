<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckClient
{
    public function handle(Request $request, Closure $next)
    {
        if (session('user_type') !== 'client') {
            abort(403, 'Geen toegang');
        }
        return $next($request);
    }
}
