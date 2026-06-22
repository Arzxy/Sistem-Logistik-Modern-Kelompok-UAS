<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AgentDeliveryService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | GET PACKAGES
    |--------------------------------------------------------------------------
    */

    public function getPackages($request)
    {

        $token =
            Session::get('token');

        $user =
            Session::get('user');

        /*
        |--------------------------------------------------------------------------
        | GET WAREHOUSE
        |--------------------------------------------------------------------------
        */

        $warehouseResponse =
            Http::withToken($token)
                ->acceptJson()
                ->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses'

                );

        if (
            !$warehouseResponse->successful()
        ) {

            return [];

        }

        $warehouse =
            collect(
                $warehouseResponse->json()['data']
            )->firstWhere(

                    'agent_id',
                    $user['id']

                );

        if (!$warehouse) {

            return [];

        }

        /*
        |--------------------------------------------------------------------------
        | GET PACKAGES
        |--------------------------------------------------------------------------
        */

        $packageResponse =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_PAKET')
                    . '/api/packages'

                );

        if (
            !$packageResponse->successful()
        ) {

            return [];

        }

        $packages =
            collect(
                $packageResponse->json()['data']
            );

        /*
        |--------------------------------------------------------------------------
        | FILTER PACKAGE
        |--------------------------------------------------------------------------
        */

        $packages =
            $packages->filter(function ($package) use ($warehouse) {

                if (
                    $package['status']
                    == 'pending_pickup'
                ) {

                    return
                        $package['origin_warehouse_id']
                        == $warehouse['id'];

                }

                if (
                    $package['status']
                    == 'at_origin_warehouse'
                ) {

                    return
                        $package['origin_warehouse_id']
                        == $warehouse['id'];

                }

                if (
                    $package['status']
                    == 'at_destination_warehouse'
                ) {

                    return
                        $package['dest_warehouse_id']
                        == $warehouse['id'];

                }

                return false;

            });

        /*
        |--------------------------------------------------------------------------
        | SEARCH FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $packages =
                $packages->filter(function ($package) use ($request) {

                    return str_contains(

                        strtolower(
                            $package['resi_number']
                        ),

                        strtolower(
                            $request->search
                        )

                    );

                });

        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->status) {

            $packages =
                $packages->where(
                    'status',
                    $request->status
                );

        }

        /*
        |--------------------------------------------------------------------------
        | ENRICH
        |--------------------------------------------------------------------------
        */

        return $packages->map(function ($package) use ($token) {

            /*
            |--------------------------------------------------------------------------
            | SENDER
            |--------------------------------------------------------------------------
            */

            $senderName = '-';

            $senderResponse =
                Http::withToken($token)
                    ->acceptJson()
                    ->get(

                        env('SERVICE_PENGGUNA')
                        . '/api/users/'
                        . $package['sender_id']

                    );

            if (
                $senderResponse->successful()
            ) {

                $sender =
                    $senderResponse->json()['data'];

                $senderName =
                    $sender['name'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | RECEIVER
            |--------------------------------------------------------------------------
            */

            $receiverName = '-';

            $receiverResponse =
                Http::withToken($token)
                    ->acceptJson()
                    ->get(

                        env('SERVICE_PENGGUNA')
                        . '/api/users/'
                        . $package['receiver_id']

                    );

            if (
                $receiverResponse->successful()
            ) {

                $receiver =
                    $receiverResponse->json()['data'];

                $receiverName =
                    $receiver['name'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | ORIGIN WAREHOUSE
            |--------------------------------------------------------------------------
            */

            $originCity = '-';

            $originResponse =
                Http::withToken($token)
                    ->acceptJson()
                    ->get(

                        env('SERVICE_PENGGUNA')
                        . '/api/warehouses/'
                        . $package['origin_warehouse_id']

                    );

            if (
                $originResponse->successful()
            ) {

                $origin =
                    $originResponse->json()['data'];

                $originCity =
                    $origin['city'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | DESTINATION WAREHOUSE
            |--------------------------------------------------------------------------
            */

            $destinationCity = '-';

            $destinationResponse =
                Http::withToken($token)
                    ->acceptJson()
                    ->get(

                        env('SERVICE_PENGGUNA')
                        . '/api/warehouses/'
                        . $package['dest_warehouse_id']

                    );

            if (
                $destinationResponse->successful()
            ) {

                $destination =
                    $destinationResponse->json()['data'];

                $destinationCity =
                    $destination['city'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | MERGE
            |--------------------------------------------------------------------------
            */

            $package['sender_name'] =
                $senderName;

            $package['receiver_name'] =
                $receiverName;

            $package['sender_city'] =
                $originCity;

            $package['receiver_city'] =
                $destinationCity;

            return $package;

        })->values();

    }

    /*
    |--------------------------------------------------------------------------
    | GET DELIVERY DETAIL
    |--------------------------------------------------------------------------
    */

    public function getDeliveryDetail($id)
    {

        $token =
            Session::get('token');

        /*
        |--------------------------------------------------------------------------
        | GET PACKAGE
        |--------------------------------------------------------------------------
        */

        $packageResponse =
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

        if (
            !$packageResponse->successful()
        ) {

            abort(404);

        }

        $package =
            $packageResponse->json()['data'];

        /*
        |--------------------------------------------------------------------------
        | SENDER
        |--------------------------------------------------------------------------
        */

        $senderName = '-';

        $senderResponse =
            Http::withToken($token)
                ->acceptJson()
                ->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/users/'
                    . $package['sender_id']

                );

        if (
            $senderResponse->successful()
        ) {

            $sender =
                $senderResponse->json()['data'];

            $senderName =
                $sender['name'] ?? '-';

        }

        /*
        |--------------------------------------------------------------------------
        | RECEIVER
        |--------------------------------------------------------------------------
        */

        $receiverName = '-';

        $receiverResponse =
            Http::withToken($token)
                ->acceptJson()
                ->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/users/'
                    . $package['receiver_id']

                );

        if (
            $receiverResponse->successful()
        ) {

            $receiver =
                $receiverResponse->json()['data'];

            $receiverName =
                $receiver['name'] ?? '-';

        }

        /*
        |--------------------------------------------------------------------------
        | ORIGIN WAREHOUSE
        |--------------------------------------------------------------------------
        */

        $originCity = '-';

        $originResponse =
            Http::withToken($token)
                ->acceptJson()
                ->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $package['origin_warehouse_id']

                );

        if (
            $originResponse->successful()
        ) {

            $origin =
                $originResponse->json()['data'];

            $originCity =
                $origin['city'] ?? '-';

        }

        /*
        |--------------------------------------------------------------------------
        | DESTINATION WAREHOUSE
        |--------------------------------------------------------------------------
        */

        $destinationCity = '-';

        $destinationResponse =
            Http::withToken($token)
                ->acceptJson()
                ->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $package['dest_warehouse_id']

                );

        if (
            $destinationResponse->successful()
        ) {

            $destination =
                $destinationResponse->json()['data'];

            $destinationCity =
                $destination['city'] ?? '-';

        }

        /*
        |--------------------------------------------------------------------------
        | MERGE PACKAGE
        |--------------------------------------------------------------------------
        */

        $package['sender_name'] =
            $senderName;

        $package['receiver_name'] =
            $receiverName;

        $package['sender_city'] =
            $originCity;

        $package['receiver_city'] =
            $destinationCity;

        /*
        |--------------------------------------------------------------------------
        | CARI WAREHOUSE AGEN SAAT INI
        |--------------------------------------------------------------------------
        */

        $user        = Session::get('user');
        $agentWareId = null;

        $warehouseListRes = Http::withToken($token)
            ->acceptJson()
            ->get(env('SERVICE_PENGGUNA') . '/api/warehouses');

        if ($warehouseListRes->successful()) {

            $agentWarehouse = collect($warehouseListRes->json()['data'])
                ->firstWhere('agent_id', $user['id'] ?? null);

            $agentWareId = $agentWarehouse['id'] ?? null;

        }

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN APAKAH AGEN INI BISA ASSIGN COURIER
        |
        | Aturan:
        | - pending_pickup / pending      → hanya agent gudang ORIGIN
        | - at_origin_warehouse           → hanya agent gudang ORIGIN
        | - at_destination_warehouse      → hanya agent gudang DESTINATION
        | - Status lainnya (in_transit, dll) → tidak ada yang bisa assign
        |--------------------------------------------------------------------------
        */

        $pkgStatus = $package['status'] ?? '';
        $canAssign = false;

        if (in_array($pkgStatus, ['pending_pickup', 'pending', 'at_origin_warehouse'])) {

            // Origin agent
            $canAssign = ($agentWareId == $package['origin_warehouse_id']);

        } elseif ($pkgStatus === 'at_destination_warehouse') {

            // Destination agent
            $canAssign = ($agentWareId == $package['dest_warehouse_id']);

        }

        /*
        |--------------------------------------------------------------------------
        | DELIVERIES
        |--------------------------------------------------------------------------
        */

        $deliveries =
            $this->getDeliveriesByPackage($id);

        /*
        |--------------------------------------------------------------------------
        | COURIERS
        | Hanya load couriers jika agen berhak assign
        |--------------------------------------------------------------------------
        */

        $couriers = $canAssign
            ? $this->getCouriers()
            : collect();

        return [

            'package'   => $package,
            'deliveries'=> $deliveries,
            'couriers'  => $couriers,
            'canAssign' => $canAssign,
            'agentWareId' => $agentWareId,

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | GET DELIVERIES BY PACKAGE
    |--------------------------------------------------------------------------
    */

    public function getDeliveriesByPackage($packageId)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_ARMADA')
                    . '/api/deliveries/package/'
                    . $packageId

                );

        if (
            !$response->successful()
        ) {

            return [];

        }

        return
            $response->json()['data'];

    }

    /*
    |--------------------------------------------------------------------------
    | GET COURIERS
    |--------------------------------------------------------------------------
    */

    public function getCouriers()
    {

        $token =
            Session::get('token');

        $user =
            Session::get('user');

        $warehouseResponse =
            Http::withToken($token)
                ->acceptJson()
                ->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses'

                );

        if (
            !$warehouseResponse->successful()
        ) {

            return [];

        }

        $warehouse =
            collect(
                $warehouseResponse->json()['data']
            )->firstWhere(

                    'agent_id',
                    $user['id']

                );

        if (!$warehouse) {

            return [];

        }

        $courierResponse =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_ARMADA')
                    . '/api/couriers',

                    [

                        'status' =>
                            'available'

                    ]

                );

        if (
            !$courierResponse->successful()
        ) {

            return [];

        }

        return collect(
            $courierResponse->json()['data']
        )->where(

                'warehouse_id',
                $warehouse['id']

            )->values();

    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGN COURIER
    |--------------------------------------------------------------------------
    */

    public function assignCourier($request)
    {

        $request->validate([

            'package_id' =>
                'required',

            'courier_id' =>
                'required',

            'delivery_type' =>
                'required'

        ]);

        $token = Session::get('token');
        $user = Session::get('user');
        $locationName = 'Warehouse';

        $warehouseResponse = Http::withToken($token)
            ->acceptJson()
            ->get(env('SERVICE_PENGGUNA') . '/api/warehouses');

        if ($warehouseResponse->successful()) {
            $warehouse = collect($warehouseResponse->json()['data'])
                ->firstWhere('agent_id', $user['id'] ?? null);
            if ($warehouse && !empty($warehouse['name'])) {
                $locationName = $warehouse['name'];
            }
        }

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->post(

                    env('SERVICE_ARMADA')
                    . '/api/deliveries',

                    [

                        'package_id' =>
                            $request->package_id,

                        'courier_id' =>
                            $request->courier_id,

                        'delivery_type' =>
                            $request->delivery_type,

                        'origin_warehouse_id' =>
                            $request->origin_warehouse_id,

                        'dest_warehouse_id' =>
                            $request->destination_warehouse_id,

                        'current_location' =>
                            $locationName,

                        'notes' =>
                            'Assigned by agent at ' . $locationName

                    ]

                );

        if (!$response->successful()) {

            $json = $response->json();

            // Ekstrak pesan error dari Laravel validation (field errors)
            $errorMsg = $json['message'] ?? 'Gagal assign courier';
            if (!empty($json['errors'])) {
                $firstField = array_key_first($json['errors']);
                $errorMsg = $json['errors'][$firstField][0] ?? $errorMsg;
            }

            return back()->with('error', $errorMsg);

        }

        // Sync package status to L2
        // Status L2 = 'assigned' saat kurir baru ditugaskan (apapun delivery_typenya)
        $delivery = $response->json()['data'];
        Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept' => 'application/json'
        ])->patch(
            env('SERVICE_PAKET') . '/api/packages/' . $request->package_id . '/status',
            [
                'status'      => 'assigned',
                'courier_id'  => (int) $request->courier_id,
                'delivery_id' => (int) $delivery['id'],
                'location'    => $locationName,
                'notes'       => 'Kurir ditugaskan untuk ' . str_replace('_', ' ', $request->delivery_type) . '.',
                'skip_tracking_log' => true,
            ]
        );

        return back()->with(

            'success',

            'Courier berhasil diassign'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | MARK PACKAGE AT WAREHOUSE
    | Digunakan agent untuk menandai paket sudah tiba di gudang
    | setelah delivery inter_warehouse selesai (status L2 = at_destination_warehouse)
    |--------------------------------------------------------------------------
    */

    public function markAtWarehouse($packageId, $warehouseType = 'origin')
    {

        $targetStatus = ($warehouseType === 'destination')
            ? 'at_destination_warehouse'
            : 'at_origin_warehouse';

        $location = ($warehouseType === 'destination')
            ? 'Gudang Tujuan'
            : 'Gudang Origin';

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->patch(

                env('SERVICE_PAKET')
                . '/api/packages/'
                . $packageId
                . '/status',

                [
                    'status'   => $targetStatus,
                    'location' => $location,
                    'notes'    => 'Paket tiba di gudang dan dikonfirmasi oleh agen.',
                ]

            );

        if (!$response->successful()) {

            return back()->with(
                'error',
                $response->json()['message']
                ?? 'Gagal mengupdate status paket.'
            );

        }

        return back()->with(
            'success',
            'Status paket berhasil dikonfirmasi: ' . str_replace('_', ' ', $targetStatus)
        );

    }

}