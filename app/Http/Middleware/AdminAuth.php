<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (!Auth::guard('admin')->check()) {
        //     return redirect('/admin/login');
        // }
        // return $next($request);

        // Check if the user is authenticated using the 'admin' guard
        $user = Auth::guard('admin')->user();

        // If the user is not logged in, redirect to the admin login page
        if (!$user) {
            return redirect('/admin/login');
        }

        // Check if the user is an Admin or Staff
        if ($user->role==1 || $user->role==2) {
            return $next($request);
        }

        // If the user is neither an Admin nor Staff, log them out and redirect
        Auth::guard('admin')->logout();
        return redirect('/admin/login')->with('error', 'Unauthorized access.');
    }
}
