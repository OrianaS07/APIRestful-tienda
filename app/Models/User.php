<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];
    /*const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';*/ // constantes de verificacion

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'verified', //si el ususario esta verificado
        // 'verification_token', // el token de ususario verificado
        // 'admin' //si es o no administrador
    ];

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name); //estable el nombre en minusculas
    }

    public function getNameAttribute($name)
    {
        return ucfirst($name);
    }
    
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'verification_token' // verificacion del tocken al iniciar
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //  Relación uno a muchos - transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relacion uno a muchos - products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
