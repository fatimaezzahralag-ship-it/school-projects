<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;



class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'solde_conges',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    
    /**
     * Retourner l'identifiant unique pour JWT (l'ID de l'utilisateur)
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retourner les claims custom à inclure dans le JWT
     * Ici, on ajoute le rôle dans le token pour vérification rapide
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,  // Ajouter le rôle au token
        ];
    }
}