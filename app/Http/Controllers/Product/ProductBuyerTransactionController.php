<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        //llamamos al contructor del padre
        parent::__construct();

        $this->middleware('transform.input:'.TransactionTransformer::class)->only(['store', 'update']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {

        $request->validate([
            'quantity' => ' required|integer|min:1',
        ]);

        if ($product->seller_id == $buyer->id) {
            return $this->errorResponse('El comprador debe de ser diferente al vendedor', 409);
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse('El comprador debe ser verificado', 409);
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse('El vendedor debe de ser verificado', 409);
        }

        if (!$product->available()) {
            return $this->errorResponse('El producto no esta disponible', 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('No es posible comprar tal cantidad de productos', 409);
        }

        /*
        transactionmétodo en la DBfachada para ejecutar un conjunto de operaciones dentro de una transacción de base de datos. Si se produce una excepción dentro de la transacción Closure, la transacción se revertirá
        */
        return DB::transaction(function () use($request, $product, $buyer) {
            //Se resta la cantidad enviada a la que hay en la DB con -=
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity'      => $request->quantity,
                'buyer_id'      => $buyer->id,
                'product_id'    => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });


    }

}
