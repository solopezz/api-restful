<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends ApiController
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
    public function index()
    {
        return $this->showAll(Transaction::all());
    }

   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //aqui retorna una respuesta con relaciones en ambos ejemplos 
        //$transaction = Transaction::with(['product.categories'])->findOrFail($id);
        //$transaction = Transaction::findOrFail(1)->load('product.categories');
        //controlador complejo TransactionCategoryController trae una respuesta similar a los ejemplos de arriba pero mas definido y sin menos informacion
        return $this->showOne($transaction, 200);
    }

  
}
