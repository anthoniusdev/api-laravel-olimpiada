<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        $allowedOrigins = [
            'https://olimpiadasdosertaoprodutivo.com/',
        ];

        $allowedMethods = [
            'GET',
            'POST',
            'PUT',
            'DELETE',
            'OPTIONS',
        ];

        $allowedHeaders = [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
        ];

        $origin = $request->headers->get('Origin');

        if (in_array($origin, $allowedOrigins)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', 'https://olimpiadasdosertaoprodutivo.com')
                ->header('Access-Control-Allow-Methods', '*')
                ->header('Access-Control-Allow-Headers', '*')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return abort(403, 'Origem não permitida.');
    }
}
