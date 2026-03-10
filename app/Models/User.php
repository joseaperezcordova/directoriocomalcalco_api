<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'id_punto_venta'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}
