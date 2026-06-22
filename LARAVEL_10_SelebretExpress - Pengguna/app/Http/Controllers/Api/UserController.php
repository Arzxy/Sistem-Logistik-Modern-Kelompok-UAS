<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // GET /api/users
    // List semua user, bisa filter ?role=kurir&city=Jakarta
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->byRole($request->role);
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->orderBy('name')->get(),
        ]);
    }

    // GET /api/users/{id}
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    // GET /api/users/phone/{phone}
    // Dipanggil L2 saat cek apakah pengirim/penerima sudah ada
    public function findByPhone($phone)
    {
        $user = User::where('phone', $phone)->active()->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User dengan nomor HP ini belum terdaftar.',
            ], 404);
        }

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    // POST /api/users
    // Admin: daftarkan agen/kurir (dengan password)
    // Agen: daftarkan pengirim/penerima (tanpa password)
    public function store(Request $request)
    {
        $needsPassword = in_array($request->role, ['admin', 'agen', 'kurir']);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => $needsPassword ? 'required|string|min:6' : 'nullable',
            'role' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'required|string|max:60',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user = User::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil didaftarkan.',
            'data' => $user,
        ], 201);
    }

    // PUT /api/users/{id}
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|nullable|email|unique:users,email,' . $id,
            'address' => 'sometimes|nullable|string',
            'city' => 'sometimes|string|max:60',
            'phone' => 'sometimes|string|unique:users,phone,' . $id,
            'is_active'      => 'sometimes|boolean',
        ]);

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data user berhasil diupdate.',
            'data' => $user->fresh(),
        ]);
    }

    // DELETE /api/users/{id} — soft deactivate
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        //$user->update(['is_active' => false]);
		// TAMBAHAN NEW
		$user->delete();
		// END
        return response()->json(['status' => 'success', 'message' => 'User dinonaktifkan.']);
    }

    // PATCH /api/users/{id}/password — ganti password
    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate(['password' => 'required|string|min:6']);
        $user->update(['password' => Hash::make($request->password)]);
        return response()->json(['status' => 'success', 'message' => 'Password berhasil diubah.']);
    }
}