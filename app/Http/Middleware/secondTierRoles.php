<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class secondTierRoles
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
        if (Auth::user()->role !== 'Administrator' && Auth::user()->role !== 'Cashier'){
            abort(403, 'Unauthorized action.');
        }
        else {
            return $next($request);
        }
    }
}
