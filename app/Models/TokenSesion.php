<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenSesion extends Model
{
    protected $table = 'tokens_sesion';

    protected $fillable = [
        'usuario_id',
        'token',
        'expira_at',
    ];

    protected $casts = [
        'expira_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
