<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\User;
use App\Scopes\HasScope;

class Buyer extends User
{

	//en el controlador de seller ya no es necesario espesificar la relacion ya que se hizo un global scope donde se pasa el nobre de la relacion en el scope que se creeo "hasScope"
	//un global scope puede ser utiizado por varios modelos mientras que un local scope solo sera de dicho o modelo osea solo uno
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new HasScope("transactions"));
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
