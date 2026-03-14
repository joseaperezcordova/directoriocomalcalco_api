<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'negocio_id',
        'dia',
        'hora_apertura',
        'hora_cierre',
        'cerrado',
    ];

    protected $casts = [
        'cerrado' => 'boolean',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
