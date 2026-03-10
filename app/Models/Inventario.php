<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model {

    protected $table = 'inventario';
    protected $fillable = [
        'id_producto',
        'id_punto_venta',
        'cantidad',
        'minimo'
    ];

    public function puntoVenta() {
        return $this->belongsTo(PuntoVenta::class, 'id_punto_venta');
    }

    public function producto() {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

}
