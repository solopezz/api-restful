<?php

namespace App\Models;

use App\Events\UserCreated;
use App\Events\UserMailCahnged;
use App\Transformers\UserTransformer;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
   use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const VERIFIED = '1';
    const NOT_VERIFIED = '0';

    const ADMIN = 'true';
    const REGULAR = 'false';

    public $transformer = UserTransformer::class; 

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

    //Otra forma de registar un evento cada ves que se actuliza un producto se ejecuta ProductStock es equivalente a usar triggers
    protected $dispatchesEvents = [
        'created' => UserCreated::class,
        'updated' => UserMailCahnged::class,
    ];

    //un mutador se utiliza el valor original de un atributo antes de hacer la insercion en la base de datos ejemplo el valor se envia asi SALVAdOr se stransforma y se guarda asi salvador
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    //accesor es cuando se modifica el valor despues de obtenerlo desde la base de datos en este ejemplo el name sera retorno en Salvador Ortiz pero en la base de datos es asi salvador ortiz
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

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
