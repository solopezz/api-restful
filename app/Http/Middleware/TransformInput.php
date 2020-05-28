<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transform)
    {
        //cambiamos los valores de los modelos antes de hacer la peticion
        $tranformedInputs = [];

        foreach ($request->request->all() as $input => $value) {
            $tranformedInputs[$transform::originalAttribute($input)] = $value;
        }

        $request->replace($tranformedInputs);
        //una ves que se hizo la peticion si hay algun error en la validacion se cambian los datos de la respuesta de la validacion por los que no son originales
        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();

            $transfomedErrors = [];
            foreach ($data->error as $field => $error) {
                $tranformedField = $transform::transformedAttribute($field);
                $transfomedErrors[$tranformedField] = str_replace($field, $tranformedField, $error);
            }

            $data->error = $transfomedErrors;
            $response->setData($data);
        }

        return $response;

    }
}
