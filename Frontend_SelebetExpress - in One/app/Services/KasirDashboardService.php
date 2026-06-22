<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class KasirDashboardService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | HELPER: Fetch all users indexed by ID (to avoid N+1 calls)
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
    private function fetchWarehousesMap(): array
    {
        $res = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PENGGUNA') . '/api/warehouses');

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

            $pkg['sender']         = $senderId   ? ($usersMap[$senderId]      ?? null) : null;
            $pkg['receiver']       = $receiverId ? ($usersMap[$receiverId]    ?? null) : null;
            $pkg['sender_city']    = $originId   ? ($warehousesMap[$originId]['city'] ?? '-') : '-';
            $pkg['receiver_city']  = $destId     ? ($warehousesMap[$destId]['city']   ?? '-') : '-';
            $pkg['origin_warehouse']      = $originId ? ($warehousesMap[$originId] ?? null) : null;
            $pkg['destination_warehouse'] = $destId   ? ($warehousesMap[$destId]   ?? null) : null;

            return $pkg;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET DASHBOARD DATA
    |--------------------------------------------------------------------------
    */
    public function getDashboardData()
    {
        $token = Session::get('token');

        // Ambil semua paket
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(env('SERVICE_PAKET') . '/api/packages', ['per_page' => 500]);

        $packages = collect([]);

        if ($response->successful()) {
            $raw      = $response->json()['data'];
            $packages = collect(is_array($raw['data'] ?? null) ? $raw['data'] : $raw);
        }

        // Ambil lookup maps sekali saja
        $usersMap      = $this->fetchUsersMap();
        $warehousesMap = $this->fetchWarehousesMap();

        // Enrich semua paket
        $packages = $this->enrichPackages($packages, $usersMap, $warehousesMap);

        $statusCounts = [
            'total'      => $packages->count(),
            'pending'    => $packages->whereIn('status', ['pending', 'pending_pickup'])->count(),
            'in_process' => $packages->whereIn('status', ['assigned', 'picked_up', 'at_origin_warehouse', 'in_transit', 'at_destination_warehouse', 'out_for_delivery'])->count(),
            'delivered'  => $packages->where('status', 'delivered')->count(),
        ];

        // Paket terbaru (10)
        $latestPackages = $packages->sortByDesc('created_at')->take(10)->values();

        return [
            'stats'          => $statusCounts,
            'latestPackages' => $latestPackages,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GET PACKAGES HISTORY
    |--------------------------------------------------------------------------
    */
    public function getPackagesHistory()
    {
        $token = Session::get('token');

        $response = Http::withToken($token)
            ->acceptJson()
            ->get(env('SERVICE_PAKET') . '/api/packages', ['per_page' => 500]);

        $packages = collect([]);

        if ($response->successful()) {
            $raw      = $response->json()['data'];
            $packages = collect(is_array($raw['data'] ?? null) ? $raw['data'] : $raw);
        }

        // Ambil lookup maps sekali saja
        $usersMap      = $this->fetchUsersMap();
        $warehousesMap = $this->fetchWarehousesMap();

        // Enrich semua paket
        $packages = $this->enrichPackages($packages, $usersMap, $warehousesMap);

        return [
            'allPackages' => $packages->sortByDesc('created_at')->values(),
        ];
    }
}
