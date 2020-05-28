<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends ApiController
{

    public function __construct()
    {
        //llamamos al contructor del padre
        parent::__construct();

        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $product = $seller->products;
        return $this->showAll($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    //se usa User por que puede que el usuario a un no sea un seller
    public function store(Request $request, User $seller)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'quantity' => ' required|integer|min:1',
            'img' => ' required|image'
        ]);

        $productData = $request->all();
        $productData['status'] = Product::OUT_OF_STOCK;
        $productData['img'] = $request->img->store('');
        $productData['seller_id'] = $seller->id;

        $product = Product::create($productData);

        //se usa metodo showOne del trait
        return $this->showOne($product, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {

        $request->validate([
            'quantity' => ' integer|min:1',
            'status' => ' in:'. Product::IN_STOCK . ',' . Product::OUT_OF_STOCK,
            'img' => ' image'
        ]);

        if ($seller->id != $product->seller_id) {
            return $this->errorResponse('El vendedor especificado no es el vendedor real del producto', 422);
        }

        //Fill" significa literalmente "llenar
        //equivalente a hacer: $user->username = 'IsraelOrtuno';
        //$request->only = aqui solo se trae los datos espesificados de la respuesta
        $product->fill($request->only([
            'name',
            'description',
            'quantity'
        ]));

        //has compruba si hay un campo en el request status
        if ($request->has('status')) {
            if (!$product->categories->count()) {
                 return $this->errorResponse('Para cambiar el estado a activo debe por lo menos tener una categoria el producto', 422);
            }
        }

        if ($request->has('img')) {
            
            Storage::delete($product->img);
            $product->img= $request->img->store('');

        }

        unset($product->categories);
        //si el modelo o el atributo permanecieron igual == true , el método es isClean
        if ($product->isClean()) {
            return $this->errorResponse('Se debe de especificar al menos un cambio', 422);
        }

        $product->save();

        //se usa metodo showOne del trait
        return $this->showOne($product, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        //esta validacion se repite se puede crear un metodo y dentro del metodo lanzar unaHttpException
        if ($seller->id != $product->seller_id) {
            return $this->errorResponse('El vendedor especificado no es el vendedor real del producto', 422);
        }

        //Eliminamos imagen del producto
        Storage::delete($product->img);

        $product->delete();
        //se usa metodo showOne del trait
        return $this->showOne($product, 200);
    }
}
