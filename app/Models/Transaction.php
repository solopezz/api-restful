<?php

namespace App\Models;

use App\Models\Buyer;
use App\Models\Product;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
	use SoftDeletes;
	
	protected $dates = ['deleted_at'];
	
    public $transformer = TransactionTransformer::class; 
	
	protected $fillable = [
		'quantity',
		'buyer_id',
		'product_id',
	];

	public function buyer()
	{
		return $this->belongsTo(Buyer::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
