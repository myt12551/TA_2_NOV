<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role === 'admin' || $request->user()->role === 'owner' || $request->user()->role === 'supervisor') return $next($request);
        return redirect()->route('dashboard')->with('status', 'Kamu tidak memiliki akses');
    }
}
