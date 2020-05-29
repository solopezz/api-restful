<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryBuyerController extends ApiController
{
  public function __construct()
  {
    parent::__construct();
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {

      $transactions = $category
      ->products()
        ->whereHas('transactions') //->importante whereHas solo trae los productos con transacciones mejora en consulta 
        //Ojo super importante solo trae los ids de los preoductos que tienen transacciones al final reduce la carga de consulta buena practica
        ->with('transactions.buyer')
        ->get()
        ->pluck('transactions')
        ->collapse()
        ->pluck('buyer')
        ->unique('id')
        ->values();

        return $this->showAll($transactions);

      }

      
    }
