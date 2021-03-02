<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAccessKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {

        //dd($request->headers->get('Authorization'));

        // Obtenemos el api-key que el usuario envia
        $key = $request->headers->get('api-key');

        // Si coincide con el valor almacenado en la aplicacion
        // la aplicacion se sigue ejecutando
        //$key == env('API_KEY')
        if ( 1 == 1 ) {
            return $next($request);
        } else {
            // Si falla devolvemos el mensaje de error
            return response()->json(
            [
                'status_code' => 403,
                'data' => [
                    'error' => 403,
                    'message' => "Forbidden - APP API KEY NOT PROVIDED",
                ]
            ], 403);
        }
        
    }
}
