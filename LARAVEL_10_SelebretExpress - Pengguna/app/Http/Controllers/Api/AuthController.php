<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // POST /api/auth/login
    // Dipanggil Frontend saat admin, agen, atau kurir login
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)
            ->whereIn('role', ['admin', 'kasir', 'agen', 'kurir'])
            ->active()
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor HP atau password salah.',
            ], 401);
        }

        // Buat token sederhana: base64 dari "id:role"
        // Contoh: base64("3:agen") = "Mzphz2Vu"
        $token = base64_encode($user->id . ':' . $user->role);

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'city' => $user->city,
                ],
            ],
        ]);
    }

    // GET /api/auth/me — cek siapa yang sedang login
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->auth_user,
        ]);
    }
}