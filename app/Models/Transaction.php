<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'user_id',
        'product_id'
    ];

    // Relacion Uno a muchos Inversa - User
    public function user()
    {
        return $this->belongsTo('App/Models/User');
    }

    // Relacion Uno a muchos Inversa - Products
    public function product()
    {
        return $this->belongsTo('App/Models/Product');
    }
}
