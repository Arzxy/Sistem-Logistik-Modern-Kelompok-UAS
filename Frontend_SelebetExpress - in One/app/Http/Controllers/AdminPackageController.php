<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PackageService;

class AdminPackageController extends Controller
{
    protected $packageService;

    public function __construct(
        PackageService $packageService
    ) {

        $this->packageService = $packageService;

    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $packages = $this->packageService
            ->getAllPackages($request);

        return view(
            'dashboard.packages.index',
            compact('packages')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK USER BY PHONE
    |--------------------------------------------------------------------------
    */

    public function checkUser($phone)
    {
        return $this->packageService
            ->checkUserByPhone($phone);
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE SHIPPING
    |--------------------------------------------------------------------------
    */

    public function calculateShipping(Request $request)
    {
        return $this->packageService
            ->calculateShipping($request);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $warehouses = $this->packageService
            ->getWarehouses();

        return view(
            'dashboard.packages.create',
            compact('warehouses')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        return $this->packageService
            ->storePackage($request);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $package = $this->packageService
            ->getPackageById($id);

        return view(

            'dashboard.packages.show',

            compact(
                'package',
            )

        );
    }

    public function registerTracking($id)
    {
        $success = $this->packageService->registerTracking($id);

        if ($success) {
            return back()->with('success', 'Paket berhasil didaftarkan ke sistem pelacakan.');
        }

        return back()->with('error', 'Gagal mendaftarkan paket ke sistem pelacakan.');
    }

}