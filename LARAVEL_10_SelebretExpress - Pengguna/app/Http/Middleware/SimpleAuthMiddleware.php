<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SimpleAuthMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek API key di header (untuk panggilan antar layanan)
        $apiKey = $request->header('X-Service-Key');
        if ($apiKey === env('INTERNAL_SERVICE_KEY')) {
            return $next($request); // layanan internal, langsung lolos
        }

        // Untuk request dari frontend: cek token login
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Token tidak ditemukan.',
            ], 401);
        }

        // Ambil user dari token sederhana (base64 encoded "id:role")
        $decoded = base64_decode(str_replace('Bearer ', '', $token));
        [$userId, $role] = explode(':', $decoded);

        $user = \App\Models\User::find($userId);
        if (!$user || !$user->is_active) {
            return response()->json(['status' => 'error', 'message' => 'User tidak valid.'], 401);
        }

        // Cek role jika middleware dipanggil dengan parameter role
        if (!empty($roles) && !in_array($user->role, $roles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak. Role tidak sesuai.',
            ], 403);
        }

        // Simpan data user ke request agar bisa dipakai di Controller
        $request->merge(['auth_user' => $user]);
        return $next($request);
    }
}