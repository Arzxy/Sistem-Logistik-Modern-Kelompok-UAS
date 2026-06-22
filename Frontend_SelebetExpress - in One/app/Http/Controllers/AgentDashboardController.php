<?php

namespace App\Http\Controllers;

use App\Services\AgentDashboardService;
use Illuminate\Http\Request;

class AgentDashboardController extends Controller
{
    protected $agentDashboardService;

    public function __construct(AgentDashboardService $agentDashboardService)
    {
        $this->agentDashboardService = $agentDashboardService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX — Dashboard utama agen gudang
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = $this->agentDashboardService->getDashboardData();

        return view('dashboard.agent.dashboard.index', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY — Riwayat semua paket di gudang agen
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $data = $this->agentDashboardService->getPackagesHistory();

        return view('dashboard.agent.packages.history', $data);
    }
}
