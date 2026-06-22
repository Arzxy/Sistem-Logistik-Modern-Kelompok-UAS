<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $data = $this->dashboardService
            ->getAdminDashboard();

        return view(
            'dashboard.admin.index',
            $data
        );
    }
}