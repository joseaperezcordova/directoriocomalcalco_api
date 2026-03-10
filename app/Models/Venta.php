<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model {

    use HasFactory;

    protected $table = 'ventas';
    protected $fillable = [
        'folio',
        'total',
        'forma_pago',
        'id_usuario',
        'id_punto_venta'
    ];
    protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /*
      |--------------------------------------------------------------------------
      | RELACIONES
      |--------------------------------------------------------------------------
     */

    // Usuario que realizó la venta
    public function vendedor() {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Punto de venta (si manejas sucursales)
    public function puntoVenta() {
        return $this->belongsTo(PuntoVenta::class, 'id_punto_venta');
    }

    // Detalle de productos vendidos
    public function detalles() {
        return $this->hasMany(VentaDetalle::class, 'id_venta');
    }

}
