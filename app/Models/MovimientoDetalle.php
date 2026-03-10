<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoDetalle extends Model
{
    use HasFactory;

    protected $table = 'movimientos_detalle';

    protected $fillable = [
        'tipo',
        'id_producto',
        'id_punto_venta_origen',
        'id_punto_venta_destino',
        'id_usuario',
        'cantidad'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'movimiento_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
