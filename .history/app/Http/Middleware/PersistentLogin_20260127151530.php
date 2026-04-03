<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersistentLogin
{
    /**
     * Handle an incoming request to check if user should remain logged in
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is not authenticated but has remember token, try to log them in
        if (!Auth::check() && $request->hasCookie('remember_web')) {
            // Laravel's remember functionality will handle this automatically
            // This middleware just extends session lifetime for active users
            $request->session()->extend();
        }

        // Extend session for authenticated users if they have remember cookie
        if (Auth::check() && $request->hasCookie('remember_web')) {
            config(['session.lifetime' => 43200]); // 30 days in minutes
        }

        return $next($request);
    }
}