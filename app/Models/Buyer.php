<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\User;

class Buyer extends User
{
    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }
}
