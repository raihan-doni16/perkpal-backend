<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceCorsHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight OPTIONS request immediately
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 204);
            return $this->addCorsHeaders($response);
        }

        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            /** @var ExceptionHandler $handler */
            $handler = app(ExceptionHandler::class);
            $handler->report($e);
            $response = $handler->render($request, $e);
        }

        return $this->addCorsHeaders($response);
    }

    private function addCorsHeaders(Response $response): Response
    {
        // Keep permissive CORS to ensure browser accepts responses even on errors.
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Origin, Accept, X-CSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'false');
        $response->headers->set('Access-Control-Max-Age', '86400'); // 24 hours

        return $response;
    }
}
