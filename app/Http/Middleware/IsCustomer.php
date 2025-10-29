<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware to ensure the authenticated user has the role of customer.
 *
 * This middleware checks the currently authenticated user and only allows
 * access to the next route if the user's role is `customer`. Otherwise,
 * it redirects them to the customer login page with a message.
 */
class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure the user is authenticated and has the 'customer' role.
        if (Auth::check() && $request->user()->role === 'customer') {
            return $next($request);
        }

        // Redirect non-customer or unauthenticated users to the customer login page.
        return redirect()->route('customer.login')->with('status', 'Anda tidak memiliki akses sebagai customer.');
    }
}
