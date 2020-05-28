<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    public function transform(Category $category)
    {
        return [
            'identifyCategory' => (int)$category->id,
            'title'  => (string)$category->name,
            'detail'  => (string)$category->description,
            'dateCreated' => (string)$category->created_at,
            'dateUpdated' => (string)$category->updated_at,
            'dateDeleted' => isset($category->deleted_at) ? (string)$category->deleted_at : null,
            //HATEOAS información devuelta serán identificadores únicos en forma de hipervínculos a otros recursos asociados.
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id),
                ],
                [
                    'rel' => 'categories.buyers',
                    'href' => route('categories.buyers.index', $category->id),
                ],
                [
                    'rel' => 'categories.products',
                    'href' => route('categories.products.index', $category->id),
                ],
                [
                    'rel' => 'categories.sellers',
                    'href' => route('categories.sellers.index', $category->id),
                ],
                [
                    'rel' => 'categories.transactions',
                    'href' => route('categories.transactions.index', $category->id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes =  [
            'identifyCategory' => 'id',
            'title'  => 'name',
            'detail'  => 'description',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $atributes =  [
            'id' => 'identifyCategory',
            'name' => 'title',
            'description' => 'detail',
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null;
    }
}
