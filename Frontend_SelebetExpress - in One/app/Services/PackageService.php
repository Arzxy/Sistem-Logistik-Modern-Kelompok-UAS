<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PackageService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | GET ALL PACKAGES
    |--------------------------------------------------------------------------
    */

    public function getAllPackages($request)
    {
        $token = Session::get('token');

        /*
        |--------------------------------------------------------------------------
        | QUERY FILTER
        |--------------------------------------------------------------------------
        */

        $query = array_filter([

            'search' => $request->search,
            'status' => $request->status,

        ]);

        /*
        |--------------------------------------------------------------------------
        | GET PACKAGES
        |--------------------------------------------------------------------------
        */

        $response = Http::withToken($token)
            ->acceptJson()
            ->get(
                env('SERVICE_PAKET') . '/api/packages',
                $query
            );

        if (!$response->successful()) {

            return [];

        }

        $packages = $response->json()['data'];

        /*
        |--------------------------------------------------------------------------
        | ENRICH USER DATA
        |--------------------------------------------------------------------------
        */

        return collect($packages)->map(function ($package) use ($token) {

            /*
            |--------------------------------------------------------------------------
            | SENDER
            |--------------------------------------------------------------------------
            */

            $sender = null;

            $senderResponse = Http::withToken($token)
                ->acceptJson()
                ->get(
                    env('SERVICE_PENGGUNA')
                    . '/api/users/'
                    . $package['sender_id']
                );

            if (
                $senderResponse->successful() &&
                isset($senderResponse->json()['data'])
            ) {

                $sender = $senderResponse->json()['data'];

            }

            $senderCityy = '-';

            $senderCityResponse = Http::withToken($token)
                ->acceptJson()
                ->get(
                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $package['origin_warehouse_id']
                );

            if (
                $senderCityResponse->successful() &&
                isset($senderCityResponse->json()['data'])
            ) {

                $senderCity = $senderCityResponse->json()['data'];

                $senderCityy = $senderCity['city'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | RECEIVER
            |--------------------------------------------------------------------------
            */

            $receiver = null;

            $receiverResponse = Http::withToken($token)
                ->acceptJson()
                ->get(
                    env('SERVICE_PENGGUNA')
                    . '/api/users/'
                    . $package['receiver_id']
                );

            if (
                $receiverResponse->successful() &&
                isset($receiverResponse->json()['data'])
            ) {

                $receiver = $receiverResponse->json()['data'];

            }

            $receiverCityy = '-';

            $receiverCityResponse = Http::withToken($token)
                ->acceptJson()
                ->get(
                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $package['dest_warehouse_id']
                );

            if (
                $receiverCityResponse->successful() &&
                isset($receiverCityResponse->json()['data'])
            ) {

                $receiverCity = $receiverCityResponse->json()['data'];

                $receiverCityy = $receiverCity['city'] ?? '-';

            }

            /*
            |--------------------------------------------------------------------------
            | MERGE
            |--------------------------------------------------------------------------
            */

            $package['sender'] = $sender;

            $package['receiver'] = $receiver;

            $package['sender_city'] = $senderCityy;

            $package['receiver_city'] = $receiverCityy;

            return $package;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET PACKAGE BY ID
    |--------------------------------------------------------------------------
    */

    public function getPackageById($id)
    {
        $token = Session::get('token');

        /*
        |--------------------------------------------------------------------------
        | GET PACKAGE
        |--------------------------------------------------------------------------
        */

        $response = Http::withHeaders([

            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept' => 'application/json'

        ])->get(

                env('SERVICE_PAKET')
                . '/api/packages/'
                . $id

            );

        if (!$response->successful()) {

            abort(404);

        }

        $package = $response->json()['data'];

        /*
        |--------------------------------------------------------------------------
        | PEMBUAT
        |--------------------------------------------------------------------------
        */

        $pembuat = null;

        $pembuatResponse = Http::withToken($token)
            ->acceptJson()
            ->get(

                env('SERVICE_PENGGUNA')
                . '/api/users/'
                . $package['created_by']

            );

        if (
            $pembuatResponse->successful() &&
            isset($pembuatResponse->json()['data'])
        ) {

            $pembuat =
                $pembuatResponse->json()['data'];

        }

        /*
        |--------------------------------------------------------------------------
        | SENDER
        |--------------------------------------------------------------------------
        */

        $sender = null;

        $senderResponse = Http::withToken($token)
            ->acceptJson()
            ->get(

                env('SERVICE_PENGGUNA')
                . '/api/users/'
                . $package['sender_id']

            );

        if (
            $senderResponse->successful() &&
            isset($senderResponse->json()['data'])
        ) {

            $sender =
                $senderResponse->json()['data'];

        }

        /*
        |--------------------------------------------------------------------------
        | RECEIVER
        |--------------------------------------------------------------------------
        */

        $receiver = null;

        $receiverResponse = Http::withToken($token)
            ->acceptJson()
            ->get(

                env('SERVICE_PENGGUNA')
                . '/api/users/'
                . $package['receiver_id']

            );

        if (
            $receiverResponse->successful() &&
            isset($receiverResponse->json()['data'])
        ) {

            $receiver =
                $receiverResponse->json()['data'];

        }

        /*
        |--------------------------------------------------------------------------
        | ORIGIN WAREHOUSE
        |--------------------------------------------------------------------------
        */

        $originWarehouse = null;

        $originResponse = Http::withToken($token)
            ->acceptJson()
            ->get(

                env('SERVICE_PENGGUNA')
                . '/api/warehouses/'
                . $package['origin_warehouse_id']

            );

        if (
            $originResponse->successful() &&
            isset($originResponse->json()['data'])
        ) {

            $originWarehouse =
                $originResponse->json()['data'];

        }

        /*
        |--------------------------------------------------------------------------
        | DESTINATION WAREHOUSE
        |--------------------------------------------------------------------------
        */

        $destinationWarehouse = null;

        $destinationResponse = Http::withToken($token)
            ->acceptJson()
            ->get(

                env('SERVICE_PENGGUNA')
                . '/api/warehouses/'
                . $package['dest_warehouse_id']

            );

        if (
            $destinationResponse->successful() &&
            isset($destinationResponse->json()['data'])
        ) {

            $destinationWarehouse =
                $destinationResponse->json()['data'];

        }

        /*
        |--------------------------------------------------------------------------
        | TRACKING LOGS (L5)
        |--------------------------------------------------------------------------
        */

        $trackingLogs = [];

        $trackingResponse = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->get(env('SERVICE_PELACAKAN') . '/api/tracking/package/' . $id);

        if ($trackingResponse->successful()) {

            $trackingJson = $trackingResponse->json();

            // L5 mungkin punya format berbeda — coba semua kemungkinan key
            $trackingLogs = $trackingJson['data']
                ?? $trackingJson['logs']
                ?? $trackingJson['tracking']
                ?? [];

            // Jika paginated: data.data
            if (isset($trackingLogs['data']) && is_array($trackingLogs['data'])) {
                $trackingLogs = $trackingLogs['data'];
            }

            // Pastikan selalu array
            if (!is_array($trackingLogs)) {
                $trackingLogs = [];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MERGE
        |--------------------------------------------------------------------------
        */

        $package['sender'] = $sender;

        $package['receiver'] = $receiver;

        $package['pembuat'] = $pembuat;

        $package['origin_warehouse'] =
            $originWarehouse;

        $package['destination_warehouse'] =
            $destinationWarehouse;

        $package['tracking_logs'] = $trackingLogs;

        return $package;
    }

    /*
    |--------------------------------------------------------------------------
    | GET WAREHOUSES
    |--------------------------------------------------------------------------
    */

    public function getWarehouses()
    {
        $token = Session::get('token');

        $response = Http::withToken($token)
            ->acceptJson()
            ->get(
                env('SERVICE_PENGGUNA') . '/api/warehouses'
            );

        if (!$response->successful()) {

            return [];

        }

        return $response->json()['data'];
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE SHIPPING
    |--------------------------------------------------------------------------
    */

    public function calculateShipping($request)
    {
        $token = Session::get('token');

        /*
        |--------------------------------------------------------------------------
        | VOLUME WEIGHT
        |--------------------------------------------------------------------------
        */

        $volumeWeight =
            (
                $request->length_cm *
                $request->width_cm *
                $request->height_cm
            ) / 6000;

        /*
        |--------------------------------------------------------------------------
        | FINAL WEIGHT
        |--------------------------------------------------------------------------
        */

        $finalWeight = max(
            $request->weight_kg,
            $volumeWeight
        );

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
                . $request->origin_warehouse_id
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
                . $request->destination_warehouse_id
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
        | API TARIFF
        |--------------------------------------------------------------------------
        */

        $response = Http::withToken($token)
            ->acceptJson()
            ->get(

                env('SERVICE_TARIF')
                . '/api/tariffs/calculate',

                [

                    'origin' =>
                        $senderCity,

                    'dest' =>
                        $receiverCity,

                    'weight' =>
                        ceil($finalWeight)

                ]

            );

        /*
        |--------------------------------------------------------------------------
        | FAILED
        |--------------------------------------------------------------------------
        */

        if (!$response->successful()) {

            return response()->json([

                'success' => false

            ]);

        }

        $data = $response->json()['data'];

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'final_weight' =>
                round($finalWeight, 1),

            'shipping_cost' =>
                $data['total_price'] ?? 0,

            'estimated_days' =>
                $data['estimated_days'] ?? '-'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK USER BY PHONE
    |--------------------------------------------------------------------------
    */

    public function checkUserByPhone($phone)
    {
        $token = Session::get('token');

        $response = Http::withToken($token)
            ->acceptJson()
            ->get(
                env('SERVICE_PENGGUNA')
                . '/api/users/phone/'
                . $phone
            );

        if (!$response->successful()) {

            return response()->json([
                'success' => false
            ]);

        }

        return response()->json([

            'success' => true,

            'data' => $response->json()['data']

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PACKAGE
    |--------------------------------------------------------------------------
    */

    public function storePackage($request)
    {
        $token = Session::get('token');

        $senderId = $request->sender_id;
        $receiverId = $request->receiver_id;

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'sender_phone' => 'required',
            'receiver_phone' => 'required',

            'sender_name' => 'required',
            'receiver_name' => 'required',

            'weight_kg' => 'required|numeric',

            'service_type' => 'required',

            'origin_warehouse_id' => 'required',

            'destination_warehouse_id' => 'required',

            'description' => 'nullable'

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE / CREATE SENDER
        |--------------------------------------------------------------------------
        */

        if ($senderId) {

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING USER
            |--------------------------------------------------------------------------
            */

            Http::withToken($token)
                ->acceptJson()
                ->put(

                    env('SERVICE_PENGGUNA')
                    . '/api/users/'
                    . $senderId,

                    [

                        'name' => $request->sender_name,
                        'phone' => $request->sender_phone,
                        'city' => $request->sender_city,
                        'address' => $request->sender_address,
                        'role' => 'pengirim',

                    ]

                );

        } else {

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW USER
            |--------------------------------------------------------------------------
            */

            $senderResponse = Http::withToken($token)
                ->acceptJson()
                ->post(

                    env('SERVICE_PENGGUNA')
                    . '/api/users',

                    [

                        'name' => $request->sender_name,
                        'phone' => $request->sender_phone,
                        'city' => $request->sender_city,
                        'address' => $request->sender_address,
                        'role' => 'pengirim',

                    ]

                );

            if (!$senderResponse->successful()) {

                return back()->with(
                    'error',
                    'Gagal membuat data pengirim'
                );

            }

            $senderId =
                $senderResponse->json()['data']['id'];

        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE / CREATE RECEIVER
        |--------------------------------------------------------------------------
        */

        if ($receiverId) {

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING USER
            |--------------------------------------------------------------------------
            */

            Http::withToken($token)
                ->acceptJson()
                ->put(

                    env('SERVICE_PENGGUNA')
                    . '/api/users/'
                    . $receiverId,

                    [

                        'name' => $request->receiver_name,
                        'phone' => $request->receiver_phone,
                        'city' => $request->destination_city,
                        'address' => $request->destination_address,
                        'role' => 'penerima',

                    ]

                );

        } else {

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW USER
            |--------------------------------------------------------------------------
            */

            $receiverResponse = Http::withToken($token)
                ->acceptJson()
                ->post(

                    env('SERVICE_PENGGUNA')
                    . '/api/users',

                    [

                        'name' => $request->receiver_name,
                        'phone' => $request->receiver_phone,
                        'city' => $request->destination_city,
                        'address' => $request->destination_address,
                        'role' => 'penerima',

                    ]

                );

            if (!$receiverResponse->successful()) {

                return back()->with(
                    'error',
                    'Gagal membuat data penerima'
                );

            }

            $receiverId =
                $receiverResponse->json()['data']['id'];

        }

        /*
        |--------------------------------------------------------------------------
        | STORE PACKAGE
        |--------------------------------------------------------------------------
        */

        $response = Http::withHeaders([

            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept' => 'application/json'

        ])->post(

                env('SERVICE_PAKET') . '/api/packages',

                [

                    'sender_id' => (int) $senderId,

                    'receiver_id' => (int) $receiverId,

                    'origin_warehouse_id' => $request->origin_warehouse_id,

                    'dest_warehouse_id' => $request->destination_warehouse_id,

                    'alamat_tujuan' => $request->destination_address,

                    'weight_kg' => $request->weight_kg,

                    'length_cm' => $request->length_cm,

                    'width_cm' => $request->width_cm,

                    'height_cm' => $request->height_cm,

                    'description' => $request->description,

                    'total_price' => $request->total_price,

                    'service_type' => $request->service_type,

                    'created_by' => Session::get('user')['id'],

                ]

            );

        /*
        |--------------------------------------------------------------------------
        | FAILED
        |--------------------------------------------------------------------------
        */

        if (!$response->successful()) {

            return back()->with(
                'error',
                $response->json()['message'] ?? $response->body()
            );

        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect('/admin/packages')
            ->with(
                'success',
                'Paket berhasil dibuat'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER TRACKING MANUALLY
    |--------------------------------------------------------------------------
    |*/
    public function registerTracking($id)
    {
        $response = Http::withHeaders([
            'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
            'Accept'        => 'application/json',
        ])->post(env('SERVICE_PAKET') . '/api/packages/' . $id . '/register-tracking');

        return $response->successful();
    }
}