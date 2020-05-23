<?php 
namespace App\Scopes;
  
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
  
class HasScope implements Scope
{
    private $relationship;


    public function __construct($relationship)
    {
        $this->relationship = $relationship;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */


    public function apply(Builder $builder, Model $model)
    {
        $builder->has($this->relationship);
    }
}