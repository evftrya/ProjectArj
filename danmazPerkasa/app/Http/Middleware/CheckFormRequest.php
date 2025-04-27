<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFormRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post') && !$request->headers->has('referer')) {
            // Redirect ke halaman lain jika tidak valid
            return redirect()->route('page.notfound'); // Ganti 'some.page' dengan rute tujuan Anda
        }

        if ($request->isMethod('get')) {
            // Jika menggunakan GET, redirect ke page not found
            return redirect()->route('page.notfound');
        }
        return $next($request);
    }
}
