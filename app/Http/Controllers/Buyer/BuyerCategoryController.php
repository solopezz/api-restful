<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {

        $categories = $buyer
        ->transactions()
        ->with('product.categories')
        ->get()
        ->pluck('product.categories')
        ->collapse() //junta los arrays de una matriz [[1],[2,3]] = [1,2,3]
        ->sortBy('id')
        ->unique('id')
        ->values();
        
        return $this->showAll($categories);
        
    }

}
