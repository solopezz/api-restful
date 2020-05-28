<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $header = 'X-Name')
    {
        
        //After Middleware Se ejecuta despues de la respuesta
        //Aqui se crea la respuesta http
        $response = $next($request);
        //Se agrega una cabecera a la respuesta
        $response->headers->set($header, config('app.name'));

        return $response;
    
    }
}
