<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'user_id',
        'image'
    ];

    public function estaDisponible()
    {
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    // Relacion Muchos a Muchos - category
    public function categories()
    {
        return $this->belongsToMany('App/Models/Transaction');
    }

    //Relacion uno a muchos inversa
    public function user()
    {
        return $this->belongsTo('App/Models/User');
    }
    
    // Relacion uno a Muchos - transactions
    public function transactions()
    {
        return $this->hasMany('App/Models/Transaction');
    }
}
