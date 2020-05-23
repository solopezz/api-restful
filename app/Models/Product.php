<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Seller;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	
	const IN_STOCK = 1;
	const OUT_OF_STOCK = 0;

	protected $dates = ['deleted_at'];
	
    protected $fillable = [
		'name', 
		'description',
		'quantity',
		'status',
		'img',
		'seller_id',
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
