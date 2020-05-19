<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
     use SoftDeletes; 

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

    protected $table = 'users';

    protected $dates = ['deleted_at'];

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
        return $this->verified == User::VERIFIED;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN;
    }

    public static function genereteVerificationToken()
    {
        return Str::random(40);
    }
}
