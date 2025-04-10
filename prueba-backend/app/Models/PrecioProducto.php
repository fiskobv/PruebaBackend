<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioProducto extends Model
{
    use HasFactory;

    protected $table = 'precios_productos';

    protected $fillable = [
        'product_id', 'currency_id', 'price'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

    public function divisa()
    {
        return $this->belongsTo(Divisa::class, 'currency_id');
    }
}

