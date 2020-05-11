<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	const IN_STOCK = 1;
	const OUT_OF_STOCK = 0;

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
}
