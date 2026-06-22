<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    public function getAllUsers()
    {
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PENGGUNA') . '/api/users');

        if (!$response->successful()) {
            return [];
        }

        return $response->json()['data'];
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER BY ID
    |--------------------------------------------------------------------------
    */

    public function getUserById($id)
    {
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PENGGUNA') . '/api/users/' . $id);

        if (!$response->successful()) {
            abort(404);
        }

        return $response->json()['data'];
    }

    /*
    |--------------------------------------------------------------------------
    | GET WAREHOUSES
    |--------------------------------------------------------------------------
    */

    public function getWarehouses()
    {
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PENGGUNA') . '/api/warehouses');

        if (!$response->successful()) {
            return [];
        }

        return $response->json()['data'] ?? [];
    }

    /*
    |--------------------------------------------------------------------------
    | GET COURIER BY USER ID (dari db armada)
    |--------------------------------------------------------------------------
    */

    public function getCourierByUserId($userId)
    {
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_ARMADA') . '/api/couriers');

        if (!$response->successful()) {
            return null;
        }

        $couriers = $response->json()['data'] ?? [];

        return collect($couriers)->firstWhere('user_id', (int) $userId);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE USER
    |--------------------------------------------------------------------------
    */

    public function storeUser($request)
    {
        $request->validate([
            'name'  => 'required',
            'phone' => 'required',
            'role'  => 'required',
            'city'  => 'required',
        ]);

        $payload = [
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'role'    => $request->role,
            'address' => $request->address,
            'city'    => $request->city,
        ];

        if (in_array($request->role, ['admin', 'kasir', 'agen', 'kurir'])) {
            $payload['password'] = $request->password;
        }

        // Simpan ke db pengguna
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->post(env('SERVICE_PENGGUNA') . '/api/users', $payload);

        if (!$response->successful()) {
            return back()->with(
                'error',
                $response->json()['message'] ?? 'Gagal tambah user'
            );
        }

        $newUser = $response->json()['data'] ?? [];
        $newUserId = $newUser['id'] ?? null;

        // Jika role kurir, juga simpan ke db armada tabel couriers
        if ($request->role === 'kurir' && $newUserId) {
            Http::withHeaders([
                'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
                'Accept'        => 'application/json',
            ])->post(env('SERVICE_ARMADA') . '/api/couriers', [
                'user_id'       => $newUserId,
                'warehouse_id'  => $request->warehouse_id,
                'name'          => $request->name,
                'phone'         => $request->phone,
                'vehicle_type'  => $request->vehicle_type  ?? 'motor',
                'vehicle_plate' => $request->vehicle_plate ?? '-',
                'status'        => 'available',
            ]);
        }

        return redirect('/admin/users')
            ->with('success', 'User berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function updateUser($request, $id)
    {
        $payload = [
            'name'      => $request->name,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'address'   => $request->address,
            'city'      => $request->city,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('role')) {
            $payload['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $payload['password'] = $request->password;
        }

        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->put(env('SERVICE_PENGGUNA') . '/api/users/' . $id, $payload);

        if (!$response->successful()) {
            return back()->with(
                'error',
                $response->json()['message'] ?? 'Gagal update user'
            );
        }

        // Sync ke db armada berdasarkan role
        if ($request->role === 'kurir') {
            // Role masih kurir → update atau buat baru
            $existingCourier = $this->getCourierByUserId($id);

            if ($existingCourier) {
                Http::withHeaders([
                    'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
                    'Accept'        => 'application/json',
                ])->put(env('SERVICE_ARMADA') . '/api/couriers/' . $existingCourier['id'], [
                    'warehouse_id'  => $request->warehouse_id ?: $existingCourier['warehouse_id'],
                    'name'          => $request->name,
                    'phone'         => $request->phone,
                    'vehicle_type'  => $request->vehicle_type  ?: ($existingCourier['vehicle_type']  ?? 'motor'),
                    'vehicle_plate' => $request->vehicle_plate ?: ($existingCourier['vehicle_plate'] ?? '-'),
                ]);
            } else {
                Http::withHeaders([
                    'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
                    'Accept'        => 'application/json',
                ])->post(env('SERVICE_ARMADA') . '/api/couriers', [
                    'user_id'       => (int) $id,
                    'warehouse_id'  => $request->warehouse_id,
                    'name'          => $request->name,
                    'phone'         => $request->phone,
                    'vehicle_type'  => $request->vehicle_type  ?? 'motor',
                    'vehicle_plate' => $request->vehicle_plate ?? '-',
                    'status'        => 'available',
                ]);
            }
        } else {
            // Role bukan kurir lagi → hapus dari tbl couriers jika ada
            $this->deleteCourierByUserId($id);
        }

        return redirect('/admin/users')
            ->with('success', 'User berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function deleteUser($id)
    {
        // Hapus dari tbl couriers armada terlebih dahulu (jika ada)
        $this->deleteCourierByUserId($id);

        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->delete(env('SERVICE_PENGGUNA') . '/api/users/' . $id);

        if (!$response->successful()) {
            return back()->with(
                'error',
                'Gagal menghapus user'
            );
        }

        return back()->with(
            'success',
            'User berhasil dihapus'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE COURIER BY USER ID (dari db armada)
    |--------------------------------------------------------------------------
    */

    public function deleteCourierByUserId($userId)
    {
        $courier = $this->getCourierByUserId($userId);

        if (!$courier) {
            return; // Tidak ada data kurir, lewati
        }

        Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->delete(env('SERVICE_ARMADA') . '/api/couriers/' . $courier['id']);
    }

    /*
    |--------------------------------------------------------------------------
    | GET USERS BY ROLE
    |--------------------------------------------------------------------------
    */

    public function getUsersByRole($role)
    {
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PENGGUNA') . '/api/users');

        if (!$response->successful()) {
            return [];
        }

        $users = $response->json()['data'];

        return collect($users)
            ->where('role', $role)
            ->values()
            ->toArray();
    }
}