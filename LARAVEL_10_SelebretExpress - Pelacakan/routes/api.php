<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TrackingController;

// ── Health Check ─────────────────────────────────────────────
Route::get('/health', function () {
    return response()->json([
        'service' => 'Layanan Pelacakan',
        'status' => 'ok',
        'port' => '8005',
        'db_tables' => [
            'tracking_logs' => \App\Models\TrackingLog::count(),
            'package_summaries' => \App\Models\PackageSummary::count(),
        ],
        'time' => now(),
    ]);
});

// ── PUBLIC — Lacak paket (tanpa auth, untuk frontend & penerima) ──
// PENTING: route spesifik harus SEBELUM route dengan parameter
Route::get('/tracking/summaries', [TrackingController::class, 'summaries']);
Route::get('/tracking/{resi}', [TrackingController::class, 'byResi']);

// ── INTERNAL — Dipakai layanan lain dengan service key ────────
Route::middleware('service.auth')->group(function () {

    // POST: L2 dan L4 kirim log baru ke sini
    // POST /api/tracking-logs (endpoint dokumentasi resmi)
    Route::post('/tracking-logs', [TrackingController::class, 'store']);

    // POST: Alias /api/tracking/log — endpoint yang dipanggil oleh
    // TrackingNotifier di L2 (Paket) dan L4 (Armada)
    Route::post('/tracking/log', [TrackingController::class, 'store']);

    // GET list semua log (untuk admin monitoring)
    // GET /api/tracking-logs?date=2024-01-15
    Route::get('/tracking-logs', [TrackingController::class, 'index']);

    // GET log by package_id (dipanggil layanan internal)
    // GET /api/tracking/package/10
    Route::get('/tracking/package/{packageId}', [TrackingController::class, 'byPackageId']);

    // DELETE: hapus log (admin)
    Route::delete('/tracking-logs/{id}', [TrackingController::class, 'destroy']);
});