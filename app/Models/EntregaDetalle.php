<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaDetalle extends Model {

    protected $table = 'entrega_detalles';
    protected $fillable = [
        'id_recepcion_detalle',
        'id_producto',
        'cantidad_entregada',
        'id_punto_venta',
        'id_usuario'
    ];
    // Si tu tabla usa timestamps (created_at, updated_at)
    public $timestamps = true;

    /*
      |--------------------------------------------------------------------------
      | RELACIONES
      |--------------------------------------------------------------------------
     */

    public function recepcionDetalle() {
        return $this->belongsTo(RecepcionDetalle::class, 'id_recepcion_detalle');
    }

}
