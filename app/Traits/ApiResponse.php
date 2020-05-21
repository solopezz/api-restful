<?php 	

//Que es un trait son un mecanismo de reutilización de código en lenguajes de herencia simple, como PHP. El objetivo de un rasgo es el de reducir las limitaciones propias de la herencia simple permitiendo que los desarrolladores reutilicen a voluntad conjuntos de métodos sobre varias clases independientes y pertenecientes a clases jerárquicas distintas.
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponse 
{
	private function successResponse($data, $code)
	{
		return response()->json($data,$code);
	}

	protected function errorResponse($message, $code)
	{	//siempre al por estandar es recomendable usar la raiz de data
		return response()->json(['error' => $message], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{	//siempre al por estandar es recomendable usar la raiz de data
		return response()->json(['data' => $collection], $code);
	}

	protected function showOne(Model $instance, $code = 200)
	{
		return response()->json(['data' => $instance], $code);
	}
}