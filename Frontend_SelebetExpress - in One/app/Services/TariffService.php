<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TariffService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | GET ALL TARIFFS
    |--------------------------------------------------------------------------
    */

    public function getAllTariffs()
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_TARIF')
                    . '/api/tariffs'

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
    | GET TARIFF BY ID
    |--------------------------------------------------------------------------
    */

    public function getTariffById($id)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->get(

                    env('SERVICE_TARIF')
                    . '/api/tariffs/'
                    . $id

                );

        if (
            !$response->successful()
        ) {

            abort(404);

        }

        return
            $response->json()['data'];

    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function storeTariff($request)
    {

        $request->validate([

            'origin_city' =>
                'required',

            'dest_city' =>
                'required',

            'price_per_kg' =>
                'required',

            'min_weight_kg' =>
                'required',

            'estimated_days' =>
                'required',

        ]);

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->post(

                    env('SERVICE_TARIF')
                    . '/api/tariffs',

                    [

                        'origin_city' =>
                            $request->origin_city,

                        'dest_city' =>
                            $request->dest_city,

                        'price_per_kg' =>
                            $request->price_per_kg,

                        'min_weight_kg' =>
                            $request->min_weight_kg,

                        'estimated_days' =>
                            $request->estimated_days,

                    ]

                );

        if (
            !$response->successful()
        ) {

            return back()->with(

                'error',

                $response->json()['message']
                ?? 'Gagal tambah tarif'

            );

        }

        return redirect('/admin/tariffs')
            ->with(
                'success',
                'Tarif berhasil dibuat'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function updateTariff(
        $request,
        $id
    ) {

        $request->validate([

            'price_per_kg' =>
                'required',

            'min_weight_kg' =>
                'required',

            'estimated_days' =>
                'required',

            'is_active' =>
                'required'

        ]);

        $user =
            Session::get('user');

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->put(

                    env('SERVICE_TARIF')
                    . '/api/tariffs/'
                    . $id,

                    [

                        'price_per_kg' =>
                            $request->price_per_kg,

                        'min_weight_kg' =>
                            $request->min_weight_kg,

                        'estimated_days' =>
                            $request->estimated_days,

                        'is_active' =>
                            $request->is_active,

                        'changed_by' =>
                            $user['id']

                    ]

                );

        if (
            !$response->successful()
        ) {

            return back()->with(

                'error',

                $response->json()['message']
                ?? 'Gagal update tarif'

            );

        }

        return redirect('/admin/tariffs')
            ->with(
                'success',
                'Tarif berhasil diupdate'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function deleteTariff($id)
    {

        $response =
            Http::withHeaders([

                'X-Service-Key' =>
                    env('INTERNAL_SERVICE_KEY'),

                'Accept' =>
                    'application/json'

            ])->delete(

                    env('SERVICE_TARIF')
                    . '/api/tariffs/'
                    . $id

                );

        if (
            !$response->successful()
        ) {

            return back()->with(

                'error',

                $response->json()['message']
                ?? 'Gagal hapus tarif'

            );

        }

        return back()->with(

            'success',

            'Tarif berhasil dihapus'

        );

    }
}