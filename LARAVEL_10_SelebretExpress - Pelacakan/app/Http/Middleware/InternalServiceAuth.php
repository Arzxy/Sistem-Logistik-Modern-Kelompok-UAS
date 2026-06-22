<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InternalServiceAuth
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-Service-Key');

        if ($key !== env('INTERNAL_SERVICE_KEY')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Service key tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}