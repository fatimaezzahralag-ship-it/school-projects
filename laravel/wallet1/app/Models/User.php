<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'solde',
        'role',
    ];

    /**
     * Les attributs qui doivent être cachés pour les sérialisations.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Obtenir l'identifiant JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retourner un tableau (tableau vide).
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }
}