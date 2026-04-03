<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'status', 'total', 'notes'
    ];

    // Les statuts possibles
    const STATUTS = [
        'en_attente'     => 'En attente',
        'en_preparation' => 'En préparation',
        'prete'          => 'Prête',
        'payee'          => 'Payée',
        'annulee'        => 'Annulée',
    ];

    // Couleurs des badges selon le statut
    const COULEURS = [
        'en_attente'     => 'yellow',
        'en_preparation' => 'blue',
        'prete'          => 'green',
        'payee'          => 'purple',
        'annulee'        => 'red',
    ];

    // Scope : commandes du jour
    public function scopeDuJour($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Scope : commandes en cours
    public function scopeEnCours($query)
    {
        return $query->whereIn('status', ['en_attente', 'en_preparation']);
    }

    // Scope : commandes validées (payées)
    public function scopeValidees($query)
    {
        return $query->where('status', 'payee');
    }

    // Un order appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un order a plusieurs order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Un order a un seul paiement
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
