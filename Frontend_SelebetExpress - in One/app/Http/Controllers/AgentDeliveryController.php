<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentDeliveryService;

class AgentDeliveryController extends Controller
{
    protected $agentDeliveryService;

    public function __construct(
        AgentDeliveryService $agentDeliveryService
    ) {

        $this->agentDeliveryService =
            $agentDeliveryService;

    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $packages =
            $this->agentDeliveryService
                ->getPackages($request);

        return view(

            'dashboard.agent.deliveries.index',

            compact(
                'packages'
            )

        );

    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

        $data =
            $this->agentDeliveryService
                ->getDeliveryDetail($id);

        return view(

            'dashboard.agent.deliveries.show',

            $data

        );

    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGN COURIER
    |--------------------------------------------------------------------------
    */

    public function assignCourier(
        Request $request
    ) {

        return $this->agentDeliveryService
            ->assignCourier($request);

    }

    /*
    |--------------------------------------------------------------------------
    | MARK AT WAREHOUSE
    | Agent mengkonfirmasi paket tiba di gudang
    |--------------------------------------------------------------------------
    */

    public function markAtWarehouse(Request $request, $packageId)
    {

        $warehouseType =
            $request->input('warehouse_type', 'origin');

        return $this->agentDeliveryService
            ->markAtWarehouse($packageId, $warehouseType);

    }

}