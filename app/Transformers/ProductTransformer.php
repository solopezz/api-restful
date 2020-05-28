<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifyProduct' => (int)$product->id,
            'title'  => (string)$product->name,
            'detail'  => (string)$product->description,
            'quantityProduct' => (string)$product->quantity,
            'statusProduct' => (string)$product->status,
            'imgProduct' => url("img/{$product->img}"),
            'seller' => (int)$product->seller_id,
            'dateCreated' => (string)$product->created_at,
            'dateUpdated' => (string)$product->updated_at,
            'dateDeleted' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
            //HATEOAS información devuelta serán identificadores únicos en forma de hipervínculos a otros recursos asociados.
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel' => 'products.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel' => 'products.categories',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel' => 'products.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.index', $product->seller_id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes =  [
            'identifyProduct' => 'id',
            'title'  => 'name',
            'detail'  => 'description',
            'quantityProduct' => 'quantity',
            'statusProduct' => 'status',
            'seller' => 'seller_id',
            'dateCreated' => 'created_at',
            'dateUpdated' => 'updated_at',
            'dateDeleted' =>'deleted_at',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $atributes =  [
            'id' => 'identifyProduct',
            'name' => 'title',
            'description' => 'detail',
            'quantity' => 'quantityProduct',
            'status' => 'statusProduct',
            'seller_id' => 'seller',
            'created_at' => 'dateCreated',
            'updated_at' => 'dateUpdated',
            'deleted_at' => 'dateDeleted',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }
}
