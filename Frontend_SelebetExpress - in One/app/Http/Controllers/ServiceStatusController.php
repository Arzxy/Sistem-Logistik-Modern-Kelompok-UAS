<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ServiceStatusController extends Controller
{
    /**
     * Daftar layanan backend yang dipantau.
     */
    private function services(): array
    {
        return [
            [
                'key'      => 'pengguna',
                'name'     => 'Layanan Pengguna',
                'desc'     => 'Autentikasi, manajemen akun & kurir',
                'url'      => rtrim(env('SERVICE_PENGGUNA', ''), '/'),
                'endpoint' => '/api/health',
                'icon'     => 'bx-user-circle',
                'color'    => 'indigo',
            ],
            [
                'key'      => 'paket',
                'name'     => 'Layanan Paket',
                'desc'     => 'Manajemen paket & pengiriman',
                'url'      => rtrim(env('SERVICE_PAKET', ''), '/'),
                'endpoint' => '/api/health',
                'icon'     => 'bx-package',
                'color'    => 'blue',
            ],
            [
                'key'      => 'tarif',
                'name'     => 'Layanan Tarif',
                'desc'     => 'Perhitungan tarif & ongkos kirim',
                'url'      => rtrim(env('SERVICE_TARIF', ''), '/'),
                'endpoint' => '/api/health',
                'icon'     => 'bx-calculator',
                'color'    => 'violet',
            ],
            [
                'key'      => 'armada',
                'name'     => 'Layanan Armada',
                'desc'     => 'Manajemen kurir & armada kendaraan',
                'url'      => rtrim(env('SERVICE_ARMADA', ''), '/'),
                'endpoint' => '/api/health',
                'icon'     => 'bx-cycling',
                'color'    => 'orange',
            ],
            [
                'key'      => 'pelacakan',
                'name'     => 'Layanan Pelacakan',
                'desc'     => 'Tracking & histori pengiriman paket',
                'url'      => rtrim(env('SERVICE_PELACAKAN', ''), '/'),
                'endpoint' => '/api/health',
                'icon'     => 'bx-map-pin',
                'color'    => 'emerald',
            ],
        ];
    }

    /**
     * Ping sebuah layanan dan kembalikan hasilnya.
     */
    private function pingService(array $service): array
    {
        $fullUrl    = $service['url'] . $service['endpoint'];
        $startTime  = microtime(true);
        $online     = false;
        $statusCode = null;
        $latency    = null;

        try {
            $response = Http::timeout(5)
                ->withHeaders(['ngrok-skip-browser-warning' => 'true'])
                ->get($fullUrl);

            $latency    = round((microtime(true) - $startTime) * 1000); // ms
            $statusCode = $response->status();
            $online     = $response->successful();

        } catch (\Exception $e) {
            $latency = round((microtime(true) - $startTime) * 1000);
        }

        // Hitung "uptime bar" relatif: online=100%, offline berdasarkan latency hint
        $uptimePercent = $online ? 100 : 0;

        // Kalau online tapi lambat, tampilkan degraded
        $status = 'offline';
        if ($online) {
            $status = ($latency !== null && $latency > 2000) ? 'degraded' : 'online';
        }

        return array_merge($service, [
            'online'        => $online,
            'status'        => $status,
            'status_code'   => $statusCode,
            'latency'       => $latency,
            'uptime_percent'=> $uptimePercent,
            'checked_at'    => now()->format('H:i:s'),
        ]);
    }

    /**
     * Tampilkan halaman status.
     */
    public function index()
    {
        $results   = array_map(fn($s) => $this->pingService($s), $this->services());
        $allOnline = collect($results)->every(fn($r) => $r['online']);
        $anyOnline = collect($results)->some(fn($r) => $r['online']);

        $overallStatus = match (true) {
            $allOnline             => 'all_operational',
            $anyOnline             => 'partial_outage',
            default                => 'major_outage',
        };

        return view('status', compact('results', 'overallStatus'));
    }

    /**
     * API endpoint: kembalikan status sebagai JSON (untuk auto-refresh AJAX).
     */
    public function api()
    {
        $results   = array_map(fn($s) => $this->pingService($s), $this->services());
        $allOnline = collect($results)->every(fn($r) => $r['online']);
        $anyOnline = collect($results)->some(fn($r) => $r['online']);

        return response()->json([
            'services'       => $results,
            'overall_status' => match (true) {
                $allOnline => 'all_operational',
                $anyOnline => 'partial_outage',
                default    => 'major_outage',
            },
            'checked_at'     => now()->toISOString(),
        ]);
    }
}
