<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthService
{
    use HandlesServiceErrors;

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required',
            'password' => 'required'
        ]);

        $url = env('SERVICE_PENGGUNA') . '/api/auth/login';

        $response = $this->safeHttp(
            fn() => Http::post($url, [
                'phone'    => $request->phone,
                'password' => $request->password,
            ]),
            $url
        );

        // Jika null = koneksi gagal, session error sudah di-flash oleh trait
        if ($response === null) {
            return back();
        }

        if (!$response->successful()) {
            return back()->with('error', 'Nomor telepon atau password salah');
        }

        $data = $response->json()['data'];

        // SIMPAN SESSION
        Session::put('token', $data['token']);
        Session::put('user', $data['user']);

        $user = $data['user'];

        // REDIRECT BERDASARKAN ROLE
        if ($user['role'] == 'admin') {
            return redirect('/admin/dashboard');
        }
        if ($user['role'] == 'kasir') {
            return redirect('/kasir/dashboard');
        }
        if ($user['role'] == 'agen') {
            return redirect('/agent/dashboard');
        }
        if ($user['role'] == 'kurir') {
            return redirect('/courier/packages');
        }

        return redirect('/');
    }
}