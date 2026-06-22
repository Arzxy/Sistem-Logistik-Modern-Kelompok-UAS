<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrackingService;

class TrackingController extends Controller
{
    protected $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function index()
    {
        return view('tracking.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'resi' => 'required'
        ]);

        $tracking = $this->trackingService->trackPackage($request->resi);

        if (!$tracking) {
            return back()->with('error', 'Nomor resi tidak ditemukan');
        }

        return view('tracking.result', compact('tracking'));
    }
}