<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'unit_price'
    ];

    // Calcule le sous-total de la ligne
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }

    // Un item appartient à une commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Un item référence un produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
