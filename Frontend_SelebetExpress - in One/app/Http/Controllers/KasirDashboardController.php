<?php

namespace App\Http\Controllers;

use App\Services\KasirDashboardService;

class KasirDashboardController extends Controller
{
    protected $kasirDashboardService;

    public function __construct(KasirDashboardService $kasirDashboardService)
    {
        $this->kasirDashboardService = $kasirDashboardService;
    }

    public function index()
    {
        $data = $this->kasirDashboardService->getDashboardData();
        return view('dashboard.kasir.dashboard.index', $data);
    }

    public function history()
    {
        $data = $this->kasirDashboardService->getPackagesHistory();
        return view('dashboard.kasir.packages.history', $data);
    }
}
