<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const VERIFIED = '1';
    const NOT_VERIFIED = '0';

    const ADMIN = 'true';
    const REGULAR = 'false';

    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified',
        'varification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'varification_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isVerified()
    {
        return $this->status == Product::IN_STOCK;
    }

    public function isAdmin()
    {
        return $this->status == Product::IN_STOCK;
    }

    public function generetaVerificationToken()
    {
        return str_random(40);
    }
}
