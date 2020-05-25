<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
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
        ->with('transactions')
        ->get()
        ->pluck('transactions')
        ->collapse()
        ->sortBy('id');
        // ->unique('id')
        // ->values();

        return $this->showAll($transactions);

    }

}
