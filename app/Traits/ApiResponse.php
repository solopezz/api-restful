<?php 	

namespace App\Traits;

//Que es un trait son un mecanismo de reutilización de código en lenguajes de herencia simple, como PHP. El objetivo de un rasgo es el de reducir las limitaciones propias de la herencia simple permitiendo que los desarrolladores reutilicen a voluntad conjuntos de métodos sobre varias clases independientes y pertenecientes a clases jerárquicas distintas.
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponse 
{
	private function successResponse($data, $code)
	{
		return response()->json($data,$code);
	}

	protected function errorResponse($message, $code)
	{	
		//siempre al por estandar es recomendable usar la raiz de data
		return response()->json(['error' => $message], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{	
		if ($collection->isEmpty()) {
			return $this->successResponse(['data' => $collection], $code);
		}

		$transformer = $collection->first()->transformer;

		//fitador
		$collection = $this->filterData($collection, $transformer);

		//ordenacion
		$collection = request()->has('sort_by') ? 
			$this->sortData($collection, $transformer) : 
			$collection;
		//paginacion
		$collection = $this->paginate($collection);
		//transormacion de propiedades del modelo
		$collection = $this->transformData($collection, $transformer);
		//utilizamos cache para no hacer muchas peticiones la la base de datos por ejemplo las respuestas duran en cache un minuto aqui se debe de poner mucha atencion es bueno usarlas en informacion que casi no cambia ya que si se elimina un usuario y otro accede a el puede causar errores e inconcintecnias en nuestro sistema
		// $collection = $this->cacheResponse($collection);

		//siempre al por estandar es recomendable usar la raiz de data
		return $this->successResponse($collection, $code);

	}

	protected function showOne(Model $instance, $code = 200)
	{
		$transformer = $instance->transformer;
		$instance = $this->transformData($instance, $transformer);
		//siempre al por estandar es recomendable usar la raiz de data
		return $this->successResponse($instance, $code);
	}

	protected function showMessage($message, $code = 200)
	{
		return response()->json(['data' => $message], $code);
	}


	protected function filterData(Collection $collection, $transformer)
	{

		foreach (request()->query() as $query => $value) {
			//?nombre=salvador $query=nombre y $value=salvador
			//Buscamos el valor normal de la porpiedad del modelo
			$attribute = $transformer::originalAttribute($query);
			//verificamos si hay valores 
			if (isset($attribute, $value)) {
				//buscamos si hay concidencias
				$collection = $collection->where($attribute, $value);
			}
		}

		return $collection;

	}

	protected function sortData(Collection $collection, $transformer)
	{
		//convertimos el atributo o lo buscamos en originalAttribute
		$attribute = $transformer::originalAttribute(request()->sort_by);

		$collection = $collection->sortby($attribute);

		return $collection;
	}

	protected function paginate(Collection $collection)
	{
		//Validamos si mandamos un tamaño para la paginacion
		Validator::make(request()->all(), [
			'per_page' => 'integer|min:2|max:50'
        ])->validate();

		//Aqui se toma el valoer page de url ?page=4
		$page = LengthAwarePaginator::resolveCurrentPage();

		//tamaño de la collecion debuelta
		$perPage = 10;
		if (request()->has('per_page')) {
			$perPage  = (int)request()->per_page;
		}
		//tamaño tolat de la collecion 
		$total = $collection->count();

		//El forPagemétodo devuelve una nueva colección que contiene los elementos que estarían presentes en un número de página determinado aqui se trae solo los elementos que se mostraran en la paginacion
		$result = $collection->forPage($page, $perPage);

		$paginate =  new LengthAwarePaginator($result, $total, $perPage, $page, [
        	'path' => LengthAwarePaginator::resolveCurrentPath(),
  		]);

		//en el path agregmaos los demas parametros de la url para que no se pierdan por ejemplo next:apiresful.dev/users?page=2&sort_by=id....
		$paginate->appends(request()->input())->links();

		return $paginate;

	}

	//el usar transofrmaciones le da un poco mas de seguridad a nuestra BD no se expone nuestra estructura de ella
	protected function transformData($data, $transformer)
	{

		$transformation = fractal($data, new $transformer);

		return $transformation->toArray();
	}

	protected function cacheResponse($data)
	{
		//url normal
		$url = request()->url();
		//obtenemos los parametros de la url
		$queryParams = request()->query();
		//los ordenamos
		ksort($queryParams);
		//los convertimos a un string
		$queryString = http_build_query($queryParams);
		//unimos la url con los parametros
		$fullUrl =  "{$url}?{$queryString}"; 

		return Cache::remember($fullUrl, 60, function() use ($data){
			return $data;
		});
	}
}