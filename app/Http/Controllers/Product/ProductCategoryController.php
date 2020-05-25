<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
        
    }

    public function update(Request $request, Product $product, Category $category)
    {
        //Si no desea separar las ID existentes, puede usar el syncWithoutDetachingmétodo: es mejor
        $product->categories()->syncWithoutDetaching($category->id);
        
        return $this->showAll($product->categories);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        //verificamos que halla una relacion 
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('La categoria no se encuentra relacionada a este producto', 409);
        }
        //detach -> elimina relacion de tabla muchos a muchos
        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);

    }
}
