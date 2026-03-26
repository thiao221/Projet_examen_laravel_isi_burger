<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{

    protected $fillable = [
        'category_id', 'name', 'slug',
        'price', 'image', 'description',
        'stock', 'archived'
    ];

    protected $casts = [
        'archived' => 'boolean',
        'price'    => 'decimal:2',
    ];



    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    // Scope : seulement les produits disponibles (en stock et non archivés)
    public function scopeDisponible($query)
    {
        return $query->where('stock', '>', 0)
            ->where('archived', false);
    }

    // Scope : filtre par nom
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'.$search.'%');
    }

    // Un produit appartient à une catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Un produit peut être dans plusieurs order items
//    public function orderItems()
//    {
//        return $this->hasMany(OrderItem::class);
//    }
}
