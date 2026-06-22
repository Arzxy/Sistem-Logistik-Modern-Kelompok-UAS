<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TrackingService
{
    use HandlesServiceErrors;
    /*
    |--------------------------------------------------------------------------
    | TRACK PACKAGE BY RESI NUMBER
    |--------------------------------------------------------------------------
    */
    public function trackPackage($resi)
    {
        $response = Http::acceptJson()->get(
            env('SERVICE_PELACAKAN') . '/api/tracking/' . $resi
        );

        if (!$response->successful()) {
            return null;
        }

        return $response->json()['data'] ?? null;
    }
}
