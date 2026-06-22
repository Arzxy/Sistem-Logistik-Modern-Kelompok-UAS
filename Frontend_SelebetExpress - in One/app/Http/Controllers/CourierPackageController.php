<?php

namespace App\Http\Controllers;

use App\Services\CourierPackageService;
use Illuminate\Http\Request;

class CourierPackageController extends Controller
{
    protected $courierPackageService;

    public function __construct(CourierPackageService $courierPackageService)
    {
        $this->courierPackageService = $courierPackageService;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: get current courier data
    |--------------------------------------------------------------------------
    */
    private function getCurrentCourier()
    {
        $user = session('user');
        if (!$user) abort(401, 'User belum login.');

        $courier = $this->courierPackageService->getCourierByUserId($user['id']);
        if (!$courier) abort(404, 'Data courier tidak ditemukan.');

        return [$user, $courier];
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX — Paket aktif (belum delivered)
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        [$user, $courier] = $this->getCurrentCourier();

        $deliveries = $this->courierPackageService->getMyDeliveries($courier['id']);

        return view('dashboard.courier.packages.index', compact('deliveries', 'courier'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        [$user, $courier] = $this->getCurrentCourier();

        $delivery = $this->courierPackageService->getDeliveryById($id);

        return view('dashboard.courier.packages.show', compact('delivery', 'courier'));
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY — Paket selesai (delivered)
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        [$user, $courier] = $this->getCurrentCourier();

        $deliveries = $this->courierPackageService->getMyHistory($courier['id']);

        return view('dashboard.courier.packages.history', compact('deliveries', 'courier'));
    }

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        [$user, $courier] = $this->getCurrentCourier();

        return view('dashboard.courier.packages.profile', compact('user', 'courier'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS KURIR (on_duty / available)
    |--------------------------------------------------------------------------
    */

    public function updateCourierStatus(Request $request)
    {
        [$user, $courier] = $this->getCurrentCourier();

        $status = $request->status; // 'on_duty' atau 'available'

        $response = $this->courierPackageService->updateCourierStatus($courier['id'], $status);

        if ($response && !$response->successful()) {
            return back()->with('error', 'Gagal mengubah status.');
        }

        $label = $status === 'on_duty' ? 'On Duty — Kamu sedang bertugas' : 'Available — Kamu siap menerima tugas';

        return back()->with('success', $label);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS DELIVERY
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, $id)
    {
        $delivery = $this->courierPackageService->getDeliveryById($id);

        $data = array_merge(
            $request->all(),
            ['package_id' => $delivery['package_id'] ?? null]
        );

        $response = $this->courierPackageService->updateDeliveryStatus($id, $data);

        if ($response && !$response->successful()) {
            $errorMsg = $response->json()['message'] ?? 'Gagal memperbarui status.';
            return back()->with('error', $errorMsg);
        }

        return back()->with('success', 'Status delivery berhasil diperbarui.');
    }
}
