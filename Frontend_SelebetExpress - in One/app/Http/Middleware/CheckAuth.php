<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Belum login? token tidak ada di session
        if (!Session::has('token')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role jika middleware dipanggil dengan parameter
        $userRole = Session::get('user.role');
        if (!empty($roles) && !in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak untuk role ini.');
        }

        return $next($request);
    }
}
