<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BannedUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_banned) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended.');
        }
        return $next($request);
    }
}
