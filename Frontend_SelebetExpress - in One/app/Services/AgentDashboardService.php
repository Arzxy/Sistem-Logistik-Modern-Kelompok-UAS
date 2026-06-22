<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AgentDashboardService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | HELPER: Fetch all users indexed by ID
    |--------------------------------------------------------------------------
    */
    private function fetchUsersMap(): array
    {
        $res = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PENGGUNA') . '/api/users', ['per_page' => 1000]);

        if (!$res->successful()) return [];

        $raw  = $res->json()['data'] ?? [];
        $list = is_array($raw['data'] ?? null) ? $raw['data'] : $raw;

        return collect($list)->keyBy('id')->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: Fetch all warehouses indexed by ID
    |--------------------------------------------------------------------------
    */
    private function fetchWarehousesMap($token): array
    {
        $res = Http::withToken($token)
            ->acceptJson()
            ->get(env('SERVICE_PENGGUNA') . '/api/warehouses');

        if (!$res->successful()) return [];

        $list = $res->json()['data'] ?? [];

        return collect($list)->keyBy('id')->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | ENRICH: Attach sender/receiver/warehouse names to each package
    |--------------------------------------------------------------------------
    */
    private function enrichPackages($packages, array $usersMap, array $warehousesMap)
    {
        return collect($packages)->map(function ($pkg) use ($usersMap, $warehousesMap) {

            $senderId   = $pkg['sender_id']   ?? null;
            $receiverId = $pkg['receiver_id'] ?? null;
            $originId   = $pkg['origin_warehouse_id'] ?? null;
            $destId     = $pkg['dest_warehouse_id']   ?? null;

            $pkg['sender']         = $senderId   ? ($usersMap[$senderId]   ?? null) : null;
            $pkg['receiver']       = $receiverId ? ($usersMap[$receiverId] ?? null) : null;
            $pkg['origin_warehouse']      = $originId ? ($warehousesMap[$originId] ?? null) : null;
            $pkg['destination_warehouse'] = $destId   ? ($warehousesMap[$destId]   ?? null) : null;

            return $pkg;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET AGENT DASHBOARD DATA
    | Mengambil semua data statistik dan daftar paket untuk dashboard agen
    |--------------------------------------------------------------------------
    */

    public function getDashboardData()
    {
        $token  = Session::get('token');
        $user   = Session::get('user');

        /*
        |--------------------------------------------------------------------------
        | CARI WAREHOUSE MILIK AGEN INI
        |--------------------------------------------------------------------------
        */

        $warehouseListRes = Http::withToken($token)
            ->acceptJson()
            ->get(env('SERVICE_PENGGUNA') . '/api/warehouses');

        $agentWarehouse = null;
        $agentWareId    = null;
        $agentWareName  = 'Gudang Anda';

        $warehousesMap = [];

        if ($warehouseListRes->successful()) {

            $warehouses = collect($warehouseListRes->json()['data'] ?? []);

            // Build warehouse map for enrichment
            $warehousesMap = $warehouses->keyBy('id')->toArray();

            $agentWarehouse = $warehouses->firstWhere('agent_id', $user['id'] ?? null);

            if ($agentWarehouse) {
                $agentWareId   = $agentWarehouse['id'];
                $agentWareName = $agentWarehouse['name'] . ' — ' . $agentWarehouse['city'];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | GET ALL PACKAGES (L2)
        | Ambil semua paket lalu filter yang relevan dengan gudang agen
        |--------------------------------------------------------------------------
        */

        $allPackages = [];

        $packagesRes = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PAKET') . '/api/packages', ['per_page' => 500]);

        if ($packagesRes->successful()) {
            $allPackages = $packagesRes->json()['data']['data']
                ?? $packagesRes->json()['data']
                ?? [];
        }

        $packages = collect($allPackages);

        /*
        |--------------------------------------------------------------------------
        | PAKET YANG RELEVAN UNTUK GUDANG INI
        | - Origin warehouse  → pending_pickup, at_origin_warehouse, picked_up, in_transit
        | - Dest warehouse    → at_destination_warehouse, out_for_delivery
        | - Selesai           → delivered (keduanya)
        |--------------------------------------------------------------------------
        */

        $relevantPackages = $packages->filter(function ($pkg) use ($agentWareId) {

            if (!$agentWareId) return false;

            $status   = $pkg['status'] ?? '';
            $isOrigin = ($pkg['origin_warehouse_id'] == $agentWareId);
            $isDest   = ($pkg['dest_warehouse_id']   == $agentWareId);

            if ($isOrigin && in_array($status, ['pending_pickup', 'pending', 'assigned', 'picked_up', 'at_origin_warehouse', 'in_transit'])) {
                return true;
            }

            if ($isDest && in_array($status, ['at_destination_warehouse', 'out_for_delivery', 'delivered'])) {
                return true;
            }

            return false;

        });

        /*
        |--------------------------------------------------------------------------
        | ENRICH WITH USER & WAREHOUSE NAMES
        |--------------------------------------------------------------------------
        */

        $usersMap = $this->fetchUsersMap();

        $relevantPackages = $this->enrichPackages($relevantPackages, $usersMap, $warehousesMap);

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        // Paket menunggu aksi agen (perlu di-assign kurir)
        $pendingAction = $relevantPackages->filter(function ($pkg) use ($agentWareId) {
            $status   = $pkg['status'] ?? '';
            $isOrigin = ($pkg['origin_warehouse_id'] == $agentWareId);
            $isDest   = ($pkg['dest_warehouse_id']   == $agentWareId);

            return ($isOrigin && in_array($status, ['pending_pickup', 'pending', 'at_origin_warehouse']))
                || ($isDest && $status === 'at_destination_warehouse');
        })->count();

        // Paket sedang dalam proses (kurir sedang bertugas)
        $inProcess = $relevantPackages->filter(function ($pkg) {
            return in_array($pkg['status'] ?? '', ['assigned', 'picked_up', 'in_transit', 'out_for_delivery']);
        })->count();

        // Paket selesai (di gudang ini sebagai destination)
        $delivered = $relevantPackages->filter(function ($pkg) {
            return ($pkg['status'] ?? '') === 'delivered';
        })->count();

        // Total paket yang pernah melewati gudang ini
        $totalRelevant = $relevantPackages->count();

        /*
        |--------------------------------------------------------------------------
        | PAKET MENUNGGU AKSI (untuk tabel utama)
        |--------------------------------------------------------------------------
        */

        $packagesNeedAction = $relevantPackages->filter(function ($pkg) use ($agentWareId) {
            $status   = $pkg['status'] ?? '';
            $isOrigin = ($pkg['origin_warehouse_id'] == $agentWareId);
            $isDest   = ($pkg['dest_warehouse_id']   == $agentWareId);

            return ($isOrigin && in_array($status, ['pending_pickup', 'pending', 'at_origin_warehouse']))
                || ($isDest && $status === 'at_destination_warehouse');
        })->take(10)->values();

        /*
        |--------------------------------------------------------------------------
        | PAKET SEDANG BERJALAN
        |--------------------------------------------------------------------------
        */

        $packagesInProcess = $relevantPackages->filter(function ($pkg) {
            return in_array($pkg['status'] ?? '', ['assigned', 'picked_up', 'in_transit', 'out_for_delivery']);
        })->take(5)->values();

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PAKET SELESAI (delivered)
        |--------------------------------------------------------------------------
        */

        $packagesHistory = $relevantPackages->filter(function ($pkg) {
            return ($pkg['status'] ?? '') === 'delivered';
        })->sortByDesc('updated_at')->take(10)->values();

        return [
            'agentWarehouse'      => $agentWarehouse,
            'agentWareName'       => $agentWareName,
            'stats' => [
                'pending_action' => $pendingAction,
                'in_process'     => $inProcess,
                'delivered'      => $delivered,
                'total'          => $totalRelevant,
            ],
            'packagesNeedAction'  => $packagesNeedAction,
            'packagesInProcess'   => $packagesInProcess,
            'packagesHistory'     => $packagesHistory,
            'packagesAllRelevant' => $relevantPackages->sortByDesc('updated_at')->values(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GET PACKAGES HISTORY (untuk halaman riwayat)
    |--------------------------------------------------------------------------
    */

    public function getPackagesHistory()
    {
        return $this->getDashboardData();
    }
}
