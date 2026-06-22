<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\WarehouseService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    protected $warehouseService;
    protected $userService;

    public function __construct(
        WarehouseService $warehouseService,
        UserService $userService
    ) {
        $this->warehouseService = $warehouseService;
        $this->userService = $userService;
    }

    public function index()
    {
        $warehouses =
            $this->warehouseService
                ->getAllWarehouses();

        return view(
            'dashboard.warehouses.index',
            compact('warehouses')
        );
    }

    public function create()
    {
        $agents =
            $this->userService
                ->getUsersByRole('agen');

        return view(
            'dashboard.warehouses.create',
            compact('agents')
        );
    }

    public function store(Request $request)
    {
        return
            $this->warehouseService
                ->storeWarehouse($request);
    }

    public function show($id)
    {
        $warehouse =
            $this->warehouseService
                ->getWarehouseById($id);

        return view(
            'dashboard.warehouses.show',
            compact('warehouse')
        );
    }

    public function edit($id)
    {
        $warehouse =
            $this->warehouseService
                ->getWarehouseById($id);

        $agents =
            $this->userService
                ->getUsersByRole('agen');

        return view(
            'dashboard.warehouses.edit',
            compact(
                'warehouse',
                'agents'
            )
        );
    }

    public function update(
        Request $request,
        $id
    ) {
        return
            $this->warehouseService
                ->updateWarehouse(
                    $request,
                    $id
                );
    }

    public function destroy($id)
    {
        return
            $this->warehouseService
                ->deleteWarehouse($id);
    }
}