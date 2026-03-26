<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // on ajoute role ici pour pouvoir le sauvegarder
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Méthode pratique : retourne true si l'utilisateur est gestionnaire
    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    // Méthode pratique : retourne true si l'utilisateur est client
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    // Un utilisateur peut avoir plusieurs commandes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
