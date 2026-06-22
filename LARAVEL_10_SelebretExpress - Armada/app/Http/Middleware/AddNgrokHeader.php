<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddNgrokHeader
{
    /**
     * Add ngrok-skip-browser-warning header to every response
     * so visitors don't get blocked by ngrok's interstitial page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('ngrok-skip-browser-warning', 'true');

        return $response;
    }
}
