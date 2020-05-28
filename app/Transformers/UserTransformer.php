<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
    public function transform(User $user)
    {
        return [
            'identifyUser' => (int)$user->id,
            'nameUser'  => (string)$user->name,
            'emailUser'  => (string)$user->email,
            'emailVerifiedUser' => isset($user->email_verified_at) ? (string)$user->email_verified_at : null,
            'verifiedUser' => (int)$user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'dateCreated' => (string)$user->created_at,
            'dateUpdated' => (string)$user->updated_at,
            'dateDeleted' => isset($user->deleted_at) ? (string)$user->deleted_at : null,
            //HATEOAS información devuelta serán identificadores únicos en forma de hipervínculos a otros recursos asociados.
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id),
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
            'isAdmin' => 'admin',
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
            'admin' => 'isAdmin',
            'created_at' => 'dateCreated',
            'updated_at' => 'dateUpdated',
            'deleted_at' => 'dateDeleted',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }
}
