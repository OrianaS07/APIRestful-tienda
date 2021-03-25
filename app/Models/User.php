<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        return $this->hasMany('App/Models/Transaction');
    }

    // Relacion uno a muchos - products
    public function products()
    {
        return $this->hasMany('App/Models/Product');
    }
}
