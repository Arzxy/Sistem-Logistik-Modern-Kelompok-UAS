<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WarehouseController;

// ─── Health Check ─────────────────────────────────────────────
Route::get('/health', function () {
    return response()->json([
        'service' => 'Layanan Pengguna',
        'status' => 'ok',
        'port' => '8001',
        'time' => now(),
    ]);
});

// ─── Auth (Publik — tidak perlu token) ────────────────────────
Route::prefix('auth')->group(function () {
    // POST /api/auth/login — untuk admin, agen, kurir
    Route::post('/login', [AuthController::class, 'login']);
});

// ─── Endpoint Internal (dipanggil layanan lain via Service Key) ─
// L2 butuh cek pengirim/penerima, L4 butuh cek kurir
Route::middleware('simple.auth')->group(function () {

    // Cari user by nomor HP — paling sering dipanggil L2
    // GET /api/users/phone/081234567890
    Route::get('/users/phone/{phone}', [UserController::class, 'findByPhone']);

    // Ambil detail user by ID — dipanggil L2, L4, L5
    Route::get('/users/{id}', [UserController::class, 'show']);

    // Ambil detail gudang by ID — dipanggil L2 saat buat resi
    Route::get('/warehouses/{id}', [WarehouseController::class, 'show']);

    // List gudang by kota — dipanggil Frontend saat pilih gudang tujuan
    Route::get('/warehouses', [WarehouseController::class, 'index']);
});

// ─── Endpoint Terproteksi (butuh login) ───────────────────────

// Admin only — kelola semua user dan gudang
Route::middleware(['simple.auth:admin'])->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::apiResource('users', UserController::class)->except(['show']);
    Route::patch('/users/{id}/password', [UserController::class, 'changePassword']);
    Route::apiResource('warehouses', WarehouseController::class)->except(['show', 'index']);
});

// Admin, Agen & Kasir — daftarkan pengirim/penerima baru
Route::middleware(['simple.auth:admin,agen,kasir'])->group(function () {
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
});