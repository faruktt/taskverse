<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (auth()->user()->role !== 'seller') {
            return redirect()->route('buyer.dashboard')->with('error', 'Seller access only.');
        }
        return $next($request);
    }
}
