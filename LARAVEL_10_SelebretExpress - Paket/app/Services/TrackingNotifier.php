<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrackingNotifier
{
    // Kirim log status baru ke L5 Pelacakan
    public static function log(
        int    $packageId,
        string $resi,
        string $status,
        string $location,
        string $notes = '',
        ?int   $courierId = null,
        ?int   $warehouseId = null
    ): void {
        try {
            Http::withHeaders([
                'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            ])->post(env('SERVICE_PELACAKAN') . '/api/tracking/log', [
                'package_id'   => $packageId,
                'resi_number'  => $resi,
                'status'       => $status,
                'location'     => $location,
                'notes'        => $notes,
                'courier_id'   => $courierId,
                'warehouse_id' => $warehouseId,
                'logged_at'    => now()->toISOString(),
            ]);
        } catch (Throwable $e) {
            // Jangan sampai error L5 menghentikan proses L2
            Log::error('TrackingNotifier gagal: ' . $e->getMessage());
        }
    }
}