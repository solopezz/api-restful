<?php

namespace App\Models;

use App\Events\ProductStock;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Transaction;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	
	const IN_STOCK = 1;
	const OUT_OF_STOCK = 0;

    public $transformer = ProductTransformer::class; 

	protected $dates = ['deleted_at'];
	
	protected $fillable = [
		'name', 
		'description',
		'quantity',
		'status',
		'img',
		'seller_id',
	];
	
	//Otra forma de registar un evento cada ves que se actuliza un producto se ejecuta ProductStock es equivalente a usar triggers
	protected $dispatchesEvents = [
		'updated' => ProductStock::class,
	];

	protected $hidden = [
		'pivot' //->se oculta la propiedad de pivot en la respuesta json
	];


	public function available()
	{
		return $this->status == Product::IN_STOCK;
	}

	public function seller()
	{
		return $this->belongsTo(Seller::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}

	public function categories()
	{
		return $this->belongsToMany(Category::class);
	}
}
