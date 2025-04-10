<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'currency_id', 'tax_cost', 'manufacturing_cost'
    ];

    public function divisa()
    {
        return $this->belongsTo(Divisa::class, 'currency_id');
    }

    public function precios()
    {
        return $this->hasMany(PrecioProducto::class, 'product_id');
    }
}
