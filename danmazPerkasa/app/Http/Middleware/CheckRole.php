<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $allowedRoles = explode('|', $roles);
        // dd(Auth::user()->role,$allowedRoles,!Auth::check(),!in_array(Auth::user()->role, $allowedRoles));
        // dd()
        // dd((!Auth::check() || !in_array(Auth::user()->role, $allowedRoles)));
        if (!Auth::check() || !in_array(Auth::user()->role, $allowedRoles)) {
            // Jika tidak, redirect ke halaman yang diinginkan
            return redirect('/PageNotFound');
        }

        return $next($request);
    }
}
