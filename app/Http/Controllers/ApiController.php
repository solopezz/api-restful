<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
//clase base de los controladores aqui se hace referencia al trait y luego se usa en cada controloador corresponidente ya no es necesario aplicarlo en cada controlador ya que esta clase lo usa y las demas clases extienden de esta
class ApiController extends Controller
{
	use ApiResponse;

	public function __construct()
	{

	}
}

