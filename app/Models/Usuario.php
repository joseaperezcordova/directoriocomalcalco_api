<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',       // admin | capturista
        'activo',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esCapturista(): bool
    {
        return $this->rol === 'capturista';
    }

    public function tokens()
    {
        return $this->hasMany(TokenSesion::class);
    }
}
