<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
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
    public function transform(Transaction $transaction)
    {
        return [
            'identifyTransaction' => (int)$transaction->id,
            'quantityProduct'  => (int)$transaction->quantity,
            'buyer'  => (int)$transaction->buyer_id,
            'product' => (int)$transaction->product_id,
            'dateCreated' => (string)$transaction->created_at,
            'dateUpdated' => (string)$transaction->updated_at,
            'dateDeleted' => isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null,
            //HATEOAS información devuelta serán identificadores únicos en forma de hipervínculos a otros recursos asociados.
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel' => 'transactions.categories',
                    'href' => route('transactions.categories.index', $transaction->id),
                ],
                [
                    'rel' => 'transactions.seller',
                    'href' => route('transactions.sellers.index', $transaction->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.index', $transaction->buyer_id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.index', $transaction->product_id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes =  [
            'identifyTransaction' => 'id',
            'quantityProduct'  => 'quantity',
            'buyer'  => 'buyer_id',
            'product' => 'product_id',
            'dateCreated' => 'created_at',
            'dateUpdated' => 'updated_at',
            'dateDeleted' => 'deleted_at',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $atributes =  [
            'id' => 'identifyTransaction',
            'quantity' => 'quantityProduct',
            'buyer_id' => 'buyer',
            'product_id' => 'product',
            'created_at' => 'dateCreated',
            'updated_at' => 'dateUpdated',
            'deleted_at' => 'dateDeleted',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }
}
