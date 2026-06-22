<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyServiceKey
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-Service-Key');

        if ($key !== env('INTERNAL_SERVICE_KEY')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses tidak diizinkan.',
            ], 403);
        }

        return $next($request);
    }
}
