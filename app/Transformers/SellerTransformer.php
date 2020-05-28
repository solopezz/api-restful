<?php

namespace App\Transformers;

use App\Models\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
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
    public function transform(Seller $seller)
    {

        return [
            //admin no es retornado por ende no sera visible
            'identifyUser' => (int)$seller->id,
            'nameUser'  => (string)$seller->name,
            'emailUser'  => (string)$seller->email,
            'emailVerifiedUser' => isset($seller->email_verified_at) ? (string)$seller->email_verified_at : null,
            'verifiedUser' => (int)$seller->verified,
            'dateCreated' => (string)$seller->created_at,
            'dateUpdated' => (string)$seller->updated_at,
            'dateDeleted' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
            //HATEOAS información devuelta serán identificadores únicos en forma de hipervínculos a otros recursos asociados.
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $seller->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $seller->id),
                ],
                [
                    'rel' => 'sellers.buyers',
                    'href' => route('sellers.buyers.index', $seller->id),
                ],
                [
                    'rel' => 'sellers.categories',
                    'href' => route('sellers.categories.index', $seller->id),
                ],
                [
                    'rel' => 'sellers.products',
                    'href' => route('sellers.products.index', $seller->id),
                ],
                [
                    'rel' => 'sellers.transactions',
                    'href' => route('sellers.transactions.index', $seller->id),
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
            'email' => 'emailUser',
            'email_verified_at' => 'emailVerifiedUser',
            'verified' => 'verifiedUser',
            'created_at' => 'dateCreated',
            'updated_at' => 'dateUpdated',
            'deleted_at' => 'dateDeleted',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }
}
