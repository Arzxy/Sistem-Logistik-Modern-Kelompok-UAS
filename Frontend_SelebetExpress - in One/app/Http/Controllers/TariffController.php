<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TariffService;

class TariffController extends Controller
{
    protected $tariffService;

    public function __construct(
        TariffService $tariffService
    ) {

        $this->tariffService =
            $tariffService;

    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $tariffs =
            $this->tariffService
                ->getAllTariffs();

        return view(

            'dashboard.tariffs.index',

            compact('tariffs')

        );

    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {

        return view(
            'dashboard.tariffs.create'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        return $this->tariffService
            ->storeTariff($request);

    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

        $tariff =
            $this->tariffService
                ->getTariffById($id);

        return view(

            'dashboard.tariffs.show',

            compact('tariff')

        );

    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {

        $tariff =
            $this->tariffService
                ->getTariffById($id);

        return view(

            'dashboard.tariffs.edit',

            compact('tariff')

        );

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        return $this->tariffService
            ->updateTariff(
                $request,
                $id
            );

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        return $this->tariffService
            ->deleteTariff($id);

    }
}