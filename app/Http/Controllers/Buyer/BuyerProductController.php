<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //Otra forma un poco mas dificl de entender 
        // $product = $buyer->load('transactions.product')->transactions->pluck('product');

        //Accedemos al querybilder de trasanctions como tal no a la relacion una ves hay podemos ejecutra o dar mas variantes a nuestra consulta en este caso agregamos la relacion with con product al acceder con get se nos trae una coleccion de transaciones con los productos relacionados lo que hacemos agremaos pluck para retornar solo la propiedad de product Pluck es para extraer ciertos valores de una colección
        $product = $buyer->transactions()->with('product')->get()->pluck('product');
        return $this->showAll($product);
    }

}
