<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userType1 = null, $userType2 = null, $userType3 = null)
    {
        if (Auth::check()) {
            if (Auth::user()->role == $userType1 || Auth::user()->role == $userType2 || Auth::user()->role == $userType3) {
                return $next($request);
            } else {
                return redirect('/')->with("message", "Maaf, Anda tidak memilii akses halaman ini!");
            }
        } else {
                return redirect('/login')->with("message", "Anda belum login! Silahkan login terlebih dahulu");
        }
    }
}
