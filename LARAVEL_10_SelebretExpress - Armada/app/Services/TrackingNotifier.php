<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrackingNotifier
{
    /**
     * Kirim log status ke L5 Pelacakan.
     * Dipanggil setiap kali status delivery berubah.
     *
     * @param int    $packageId   ID paket (dari L2)
     * @param int    $courierId   ID kurir
     * @param string $status      Status baru pengiriman
     * @param string $location    Lokasi saat ini
     * @param string|null $notes  Catatan tambahan (opsional)
     */
    public function notify(
        int $packageId,
        int $courierId,
        string $status,
        string $location,
        ?string $notes = null
    ): bool {
        try {
            $response = Http::withHeaders([
                'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            ])->post(env('SERVICE_PELACAKAN') . '/api/tracking/log', [
                'package_id'  => $packageId,
                'courier_id'  => $courierId,
                'status'      => $status,
                'location'    => $location,
                'notes'       => $notes,
                'logged_at'   => now()->toIso8601String(),
            ]);

            if (!$response->successful()) {
                Log::warning('TrackingNotifier: L5 merespon error', [
                    'package_id' => $packageId,
                    'status'     => $status,
                    'response'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            // Jangan gagalkan operasi L4 hanya karena L5 tidak merespon
            Log::error('TrackingNotifier: Gagal koneksi ke L5', [
                'package_id' => $packageId,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }
}