<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\PackageController;

// ── Health check (publik, tanpa auth) ────────────────────────
Route::get('/health', function () {
    return response()->json([
        'service' => 'Layanan Paket',
        'status'  => 'ok',
        'db'      => DB::connection()->getPdo() ? 'connected' : 'error',
        'time'    => now(),
    ]);
});

// ── Endpoint publik ───────────────────────────────────────────
// Lacak paket by resi (bisa diakses oleh siapa saja via Frontend)
Route::get('/packages/resi/{resi}', [PackageController::class, 'showByResi']);

// ── Endpoint internal (wajib X-Service-Key) ───────────────────
Route::middleware('service.key')->group(function () {

    // List semua paket (dengan filter)
    Route::get('/packages',         [PackageController::class, 'index']);

    // Detail paket by ID
    Route::get('/packages/{id}',    [PackageController::class, 'show']);

    // Buat paket baru / generate resi
    Route::post('/packages',        [PackageController::class, 'store']);

    // Update status paket (dipanggil L4 saat kurir update)
    Route::patch('/packages/{id}/status', [PackageController::class, 'updateStatus']);

    // Statistik untuk dashboard
    Route::get('/packages/stats',   [PackageController::class, 'stats']);

    // Registrasi manual pelacakan paket
    Route::post('/packages/{id}/register-tracking', [PackageController::class, 'registerTracking']);

});