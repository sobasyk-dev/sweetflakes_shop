<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in AND is an customer
        // if (Auth::check() && Auth::user()->role === 'customer') {
        //     return $next($request);
        // }

        // If not customer, kick them back to the home page with an error
        // return redirect('/')->with('error', 'Unauthorized access.');
    }
}
