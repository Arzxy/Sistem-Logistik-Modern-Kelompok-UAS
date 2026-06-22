<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardService
{
    use HandlesServiceErrors;
    public function getAdminDashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | TOKEN
        |--------------------------------------------------------------------------
        */

        $token = Session::get('token');

        /*
        |--------------------------------------------------------------------------
        | DEFAULT DATA
        |--------------------------------------------------------------------------
        */

        $packages = [];
        $couriers = [];

        /*
        |--------------------------------------------------------------------------
        | SERVICE PAKET
        |--------------------------------------------------------------------------
        */

        $packagesResponse = Http::withToken($token)
            ->acceptJson()
            ->get(
                env('SERVICE_PAKET') . '/api/packages'
            );

        if (
            $packagesResponse->successful() &&
            isset($packagesResponse->json()['data'])
        ) {

            $packages = $packagesResponse->json()['data'];

        }

        /*
        |--------------------------------------------------------------------------
        | SERVICE ARMADA
        |--------------------------------------------------------------------------
        */


        $courierResponse = Http::withHeaders([

            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept' => 'application/json'

        ])->get(
                env('SERVICE_ARMADA') . '/api/couriers'
            );

        if (
            $courierResponse->successful() &&
            isset($courierResponse->json()['data'])
        ) {

            $couriers = $courierResponse->json()['data'];

        }

        /*
        |--------------------------------------------------------------------------
        | ENRICH PACKAGE DATA
        |--------------------------------------------------------------------------
        */

        $packages = collect($packages)->map(function ($package) use ($token) {

            /*
            |--------------------------------------------------------------------------
            | SENDER
            |--------------------------------------------------------------------------
            */

            $senderCity = '-';

            $senderResponse = Http::withToken($token)
                ->acceptJson()
                ->get(
                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $package['origin_warehouse_id']
                );

            if (
                $senderResponse->successful() &&
                isset($senderResponse->json()['data'])
            ) {

                $sender = $senderResponse->json()['data'];

                $senderCity = $sender['city'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | RECEIVER
            |--------------------------------------------------------------------------
            */

            $receiverCity = '-';

            $receiverResponse = Http::withToken($token)
                ->acceptJson()
                ->get(
                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $package['dest_warehouse_id']
                );

            if (
                $receiverResponse->successful() &&
                isset($receiverResponse->json()['data'])
            ) {

                $receiver = $receiverResponse->json()['data'];

                $receiverCity = $receiver['city'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | MERGE
            |--------------------------------------------------------------------------
            */

            $package['sender_city'] = $senderCity;

            $package['receiver_city'] = $receiverCity;

            return $package;
        });

        /*
        |--------------------------------------------------------------------------
        | PACKAGE STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalPackages = $packages->count();

        $pendingPackages = $packages
            ->filter(fn($p) => in_array($p['status'], ['pending', 'pending_pickup']))
            ->count();

        $transitPackages = $packages
            ->filter(fn($p) => in_array($p['status'], ['assigned', 'picked_up', 'at_origin_warehouse', 'in_transit', 'at_destination_warehouse', 'out_for_delivery']))
            ->count();

        $deliveredPackages = $packages
            ->where('status', 'delivered')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | COURIER STATISTICS
        |--------------------------------------------------------------------------
        */

        $availableCouriers = collect($couriers)
            ->where('status', 'available')
            ->count();

        $activeCouriers = collect($couriers)
            ->where('status', 'on_duty')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | LATEST PACKAGES
        |--------------------------------------------------------------------------
        */

        $latestPackages = $packages
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return [

            'packages' => $latestPackages,

            'stats' => [

                'total_packages' => $totalPackages,

                'pending_packages' => $pendingPackages,

                'transit_packages' => $transitPackages,

                'delivered_packages' => $deliveredPackages,

                'available_couriers' => $availableCouriers,

                'active_couriers' => $activeCouriers,

            ]

        ];
    }
}