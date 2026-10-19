<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BuyerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (auth()->user()->role !== 'buyer') {
            return redirect()->route('seller.dashboard')->with('error', 'Buyer access only.');
        }
        return $next($request);
    }
}
