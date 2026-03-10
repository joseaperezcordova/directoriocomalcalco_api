<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecepcionDetalle extends Model
{
    protected $table = 'recepcion_detalles';
    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class);
    }
}
