<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourierController;
use App\Http\Controllers\Api\DeliveryController;

// Health check — tidak butuh service key
Route::get('/health', function () {
    return response()->json([
        'service' => 'Layanan Armada',
        'status'  => 'ok',
        'time'    => now(),
    ]);
});

// ── Semua route di bawah butuh X-Service-Key header ──────────
Route::middleware('service.key')->group(function () {

    // ── Courier Routes ───────────────────────────────────────
    Route::apiResource('couriers', CourierController::class);
    // GET    /api/couriers           → index  (list semua kurir)
    // POST   /api/couriers           → store  (daftarkan kurir baru)
    // GET    /api/couriers/{id}      → show   (detail satu kurir)
    // PUT    /api/couriers/{id}      → update (ubah data kurir)
    // DELETE /api/couriers/{id}      → destroy (nonaktifkan kurir)

    // Riwayat delivery kurir tertentu
    Route::get('/couriers/{id}/deliveries', [CourierController::class, 'deliveries']);

    // ── Delivery Routes ──────────────────────────────────────
    // PENTING: route spesifik (by-package) HARUS sebelum {id}
    Route::get('/deliveries/by-package/{packageId}', [DeliveryController::class, 'byPackage']);

    Route::get('/deliveries/package/{packageId}', [DeliveryController::class, 'getAllbyPackage']);
	
    Route::apiResource('deliveries', DeliveryController::class)->only([
        'index', 'show', 'store',
    ]);
    // GET  /api/deliveries           → index  (list delivery, bisa filter)
    // GET  /api/deliveries/{id}      → show   (detail satu delivery)
    // POST /api/deliveries           → store  (assign kurir ke paket)

    // Update status pengiriman (kurir pakai ini)
    Route::patch('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus']);
    // PATCH /api/deliveries/{id}/status
});