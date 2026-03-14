<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $table = 'negocios';

    protected $fillable = [
        'nombre',
        'categoria_id',
        'telefono',
        'whatsapp',
        'facebook',
        'instagram',
        'direccion',
        'colonia',
        'referencia',
        'lat',
        'lng',
        'descripcion',
        'foto',
        'servicio_domicilio',
        'plan',
        'estado',
        'motivo_rechazo',
        'capturado_por',
        'aprobado_por',
        'aprobado_at',
    ];

    protected $casts = [
        'lat'                => 'float',
        'lng'                => 'float',
        'servicio_domicilio' => 'boolean',
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function capturista()
    {
        return $this->belongsTo(Usuario::class, 'capturado_por');
    }

    // Scopes
    public function scopePublicos($query)
    {
        return $query->where('estado', 'aprobado');
    }
}
