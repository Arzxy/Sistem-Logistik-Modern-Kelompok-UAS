<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WarehouseService
{
    use HandlesServiceErrors;
    public function getAllWarehouses()
    {
        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses'

                );

        if (!$response->successful()) {

            return [];

        }

        return
            $response->json()['data'];
    }

    public function getWarehouseById($id)
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

            abort(404);

        }

        return
            $response->json()['data'];
    }

    public function storeWarehouse($request)
    {
        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->post(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses',

                    [

                        'agent_id' =>
                            $request->agent_id,

                        'name' =>
                            $request->name,

                        'city' =>
                            $request->city,

                        'address' =>
                            $request->address,

                        'phone' =>
                            $request->phone,

                    ]

                );

        if (!$response->successful()) {

            return back()->with(

                'error',

                $response->json()['message']
                ?? 'Gagal tambah gudang'

            );

        }

        return redirect()
            ->route('warehouses.index')
            ->with(
                'success',
                'Gudang berhasil dibuat'
            );
    }

    public function updateWarehouse(
        $request,
        $id
    ) {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->put(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $id,

                    [

                        'agent_id' =>
                            $request->agent_id,

                        'name' =>
                            $request->name,

                        'city' =>
                            $request->city,

                        'address' =>
                            $request->address,

                        'phone' =>
                            $request->phone,

                        'is_active' =>
                            $request->is_active

                    ]

                );

        if (!$response->successful()) {

            return back()->with(

                'error',

                $response->json()['message']
                ?? 'Gagal update gudang'

            );

        }

        return redirect()
            ->route('warehouses.index')
            ->with(
                'success',
                'Gudang berhasil diupdate'
            );
    }

    public function deleteWarehouse($id)
    {
        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->delete(

                    env('SERVICE_PENGGUNA')
                    . '/api/warehouses/'
                    . $id

                );

        if (!$response->successful()) {

            return back()->with(

                'error',

                'Gagal hapus gudang'

            );

        }

        return redirect()
            ->route('warehouses.index')
            ->with(
                'success',
                'Gudang berhasil dinonaktifkan'
            );
    }
}