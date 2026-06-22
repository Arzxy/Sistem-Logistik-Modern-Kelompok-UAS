<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TariffController;

// Health check — untuk cek apakah layanan hidup dari laptop lain
Route::get('/health', function () {
    return response()->json([
        'service' => 'Layanan Tarif',
        'status'  => 'ok',
        'time'    => now(),
    ]);
});

// ─── Tariff Routes ────────────────────────────────────────────
// PENTING: route spesifik (calculate, bulk) harus SEBELUM {id}

// Kalkulasi ongkir — dipanggil L2 & Frontend
// GET /api/tariffs/calculate?origin=Jakarta&dest=Bandung&weight=2.5
Route::get('/tariffs/calculate', [TariffController::class, 'calculate']);

// Bulk insert — admin upload banyak tarif sekaligus
// POST /api/tariffs/bulk
Route::post('/tariffs/bulk', [TariffController::class, 'bulk']);

// CRUD standar
Route::apiResource('tariffs', TariffController::class);
// Menghasilkan:
// GET    /api/tariffs           → index()   (list semua)
// POST   /api/tariffs           → store()   (buat baru)
// GET    /api/tariffs/{id}      → show()    (detail satu)
// PUT    /api/tariffs/{id}      → update()  (ubah tarif)
// DELETE /api/tariffs/{id}      → destroy() (nonaktifkan)

// Riwayat perubahan harga tarif tertentu
// GET /api/tariffs/{id}/logs
Route::get('/tariffs/{id}/logs', [TariffController::class, 'logs']);