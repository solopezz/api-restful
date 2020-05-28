<?php

namespace App\Transformers;

use App\Models\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
    public function transform(Buyer $buyer)
    {

        return [
            'identifyUser' => (int)$buyer->id,
            'nameUser'  => (string)$buyer->name,
            'emailUser'  => (string)$buyer->email,
            'emailVerifiedUser' => isset($buyer->email_verified_at) ? (string)$buyer->email_verified_at : null,
            'verifiedUser' => (int)$buyer->verified,
            'dateCreated' => (string)$buyer->created_at,
            'dateUpdated' => (string)$buyer->updated_at,
            'dateDeleted' => isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null,
            //HATEOAS información devuelta serán identificadores únicos en forma de hipervínculos a otros recursos asociados.
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('buyers.show', $buyer->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $buyer->id),
                ],
                [
                    'rel' => 'buyers.categories',
                    'href' => route('buyers.categories.index', $buyer->id),
                ],
                [
                    'rel' => 'buyers.products',
                    'href' => route('buyers.products.index', $buyer->id),
                ],
                [
                    'rel' => 'buyers.sellers',
                    'href' => route('buyers.sellers.index', $buyer->id),
                ],
                [
                    'rel' => 'buyers.transactions',
                    'href' => route('buyers.transactions.index', $buyer->id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes =  [
            'identifyUser' => 'id',
            'nameUser'  => 'name',
            'emailUser'  => 'email',
            'emailVerifiedUser' => 'email_verified_at',
            'verifiedUser' => 'verified',
            'dateCreated' => 'created_at',
            'dateUpdated' => 'updated_at',
            'dateDeleted' => 'deleted_at',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $atributes =  [
            'id' => 'identifyUser',
            'name' => 'nameUser',
            'email' => 'emailUser' ,
            'email_verified_at' => 'emailVerifiedUser',
            'verified'=> 'verifiedUser',
            'created_at' => 'dateCreated',
            'updated_at' => 'dateUpdated',
            'deleted_at' => 'dateDeleted',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }

}
