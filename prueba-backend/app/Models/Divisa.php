<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'symbol', 'exchange_rate'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'currency_id');
    }

    public function precios()
    {
        return $this->hasMany(PrecioProducto::class, 'currency_id');
    }
}

