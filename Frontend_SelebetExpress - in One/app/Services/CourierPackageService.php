<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CourierPackageService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | GET MY DELIVERIES
    |--------------------------------------------------------------------------
    */

    public function getMyDeliveries($courierId)
    {
        $deliveries = $this->fetchDeliveries($courierId);

        // Hanya yang BELUM selesai (aktif)
        return collect($deliveries)
            ->filter(fn($d) => ($d['status'] ?? '') !== 'delivered')
            ->values()
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | GET MY HISTORY (delivered)
    |--------------------------------------------------------------------------
    */

    public function getMyHistory($courierId)
    {
        $deliveries = $this->fetchDeliveries($courierId);

        // Hanya yang SUDAH selesai
        return collect($deliveries)
            ->filter(fn($d) => ($d['status'] ?? '') === 'delivered')
            ->sortByDesc('updated_at')
            ->values()
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH DELIVERIES (base method)
    |--------------------------------------------------------------------------
    */

    private function fetchDeliveries($courierId): array
    {
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_ARMADA') . '/api/deliveries', [
            'courier_id' => $courierId,
        ]);

        if (!$response->successful()) {
            return [];
        }

        $deliveries = $response->json()['data']['data']
            ?? $response->json()['data']
            ?? [];

        return collect($deliveries)
            ->map(function ($delivery) {
                $delivery['package'] = $this->getPackageById($delivery['package_id']);
                return $delivery;
            })
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE COURIER STATUS (on_duty / available)
    |--------------------------------------------------------------------------
    */

    public function updateCourierStatus($courierId, string $status)
    {
        return Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->put(env('SERVICE_ARMADA') . '/api/couriers/' . $courierId, [
            'status' => $status,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GET DELIVERY BY ID
    |--------------------------------------------------------------------------
    */

    public function getDeliveryById($id)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_ARMADA')
                    . '/api/deliveries/'
                    . $id

                );

        if (!$response->successful()) {

            abort(404);

        }

        $delivery =
            $response->json()['data'];

        $delivery['package'] =
            $this->getPackageById(
                $delivery['package_id']
            );

        return $delivery;

    }

    /*
    |--------------------------------------------------------------------------
    | GET PACKAGE
    |--------------------------------------------------------------------------
    */

    private function getPackageById($id)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_PAKET')
                    . '/api/packages/'
                    . $id

                );

        if (!$response->successful()) {

            return [];

        }

        $package =
            $response->json()['data'];

        /*
        |--------------------------------------------------------------------------
        | SENDER
        |--------------------------------------------------------------------------
        */

        if (
            isset($package['sender_id'])
        ) {

            $package['sender'] =
                $this->getUserById(
                    $package['sender_id']
                );

        }

        /*
        |--------------------------------------------------------------------------
        | RECEIVER
        |--------------------------------------------------------------------------
        */

        if (
            isset($package['receiver_id'])
        ) {

            $package['receiver'] =
                $this->getUserById(
                    $package['receiver_id']
                );

        }

        /*
		|--------------------------------------------------------------------------
		| ORIGIN WAREHOUSE
		|--------------------------------------------------------------------------
		*/

        if (
            isset($package['origin_warehouse_id'])
        ) {

            $package['origin_warehouse'] =
                $this->getWarehouseById(
                    $package['origin_warehouse_id']
                );

        }

        /*
        |--------------------------------------------------------------------------
        | DESTINATION WAREHOUSE
        |--------------------------------------------------------------------------
        */

        if (
            isset($package['dest_warehouse_id'])
        ) {

            $package['destination_warehouse'] =
                $this->getWarehouseById(
                    $package['dest_warehouse_id']
                );

        }

        return $package;

    }

    /*
    |--------------------------------------------------------------------------
    | GET WAREHOUSE
    |--------------------------------------------------------------------------
    */

    private function getWarehouseById($id)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $id

                );

        if (!$response->successful()) {

            return null;

        }

        return
            $response->json()['data'];

    }

    /*
    |--------------------------------------------------------------------------
    | GET USER
    |--------------------------------------------------------------------------
    */

    private function getUserById($id)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/users/'
                    . $id

                );

        if (!$response->successful()) {

            return null;

        }

        return
            $response->json()['data'];

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DELIVERY STATUS
    |--------------------------------------------------------------------------
    */

    public function updateDeliveryStatus($id, $data)
    {

        /*
        |--------------------------------------------------------------------------
        | UPDATE L4 (ARMADA) DELIVERY STATUS
        |--------------------------------------------------------------------------
        */

        $response = Http::withHeaders([

            'X-Service-Key' =>
                env('INTERNAL_SERVICE_KEY'),

            'Accept' =>
                'application/json'

        ])->patch(

                env('SERVICE_ARMADA')
                . '/api/deliveries/'
                . $id
                . '/status',

                [
                    'delivery_type' => $data['delivery_type'],
                    'status'        => $data['status'],
                    'location'      => $data['location'] ?? 'Dalam Perjalanan',
                    'notes'         => $data['notes'] ?? null,
                ]

            );

        if (!$response->successful()) {
            return $response;
        }

        /*
        |--------------------------------------------------------------------------
        | SYNC PACKAGE STATUS (L2)
        | Pemetaan status delivery → status paket:
        |
        | pickup + picked_up          → picked_up
        | pickup + delivered          → at_origin_warehouse
        |
        | inter_warehouse + picked_up → in_transit
        | inter_warehouse + in_transit→ in_transit
        | inter_warehouse + delivered → at_destination_warehouse
        |
        | last_mile + picked_up       → out_for_delivery
        | last_mile + out_for_delivery→ out_for_delivery
        | last_mile + delivered       → delivered
        |--------------------------------------------------------------------------
        */

        $deliveryType = $data['delivery_type'] ?? null;
        $deliveryStatus = $data['status'] ?? null;
        $packageId = $data['package_id'] ?? null;

        $packageStatus = $this->resolvePackageStatus(
            $deliveryType,
            $deliveryStatus
        );

        if ($packageStatus && $packageId) {

            Http::withHeaders([

                'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
                'Accept'        => 'application/json'

            ])->patch(

                env('SERVICE_PAKET')
                . '/api/packages/'
                . $packageId
                . '/status',

                [
                    'status'   => $packageStatus,
                    'location' => $data['location'] ?? null,
                    'notes'    => $data['notes'] ?? null,
                    'skip_tracking_log' => true,
                ]

            );

        }

        return $response;

    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE PACKAGE STATUS FROM DELIVERY TYPE + DELIVERY STATUS
    |--------------------------------------------------------------------------
    */

    private function resolvePackageStatus(
        string $deliveryType,
        string $deliveryStatus
    ): ?string {

        $map = [

            'pickup' => [
                'picked_up' => 'picked_up',
                'delivered' => 'at_origin_warehouse',
            ],

            'inter_warehouse' => [
                'picked_up'  => 'in_transit',
                'in_transit' => 'in_transit',
                'delivered'  => 'at_destination_warehouse',
            ],

            'last_mile' => [
                'picked_up'        => 'out_for_delivery',
                'out_for_delivery' => 'out_for_delivery',
                'delivered'        => 'delivered',
            ],

        ];

        return $map[$deliveryType][$deliveryStatus] ?? null;

    }

    public function getCourierByUserId($userId)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_ARMADA')
                    . '/api/couriers'

                );

        if (!$response->successful()) {

            return null;

        }

        $couriers =
            $response->json()['data']
            ?? [];

        return collect($couriers)

            ->firstWhere(
                'user_id',
                $userId
            );

    }
}