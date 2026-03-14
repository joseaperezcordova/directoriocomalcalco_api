<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nombre', 'icono', 'activo'];

    public function negocios()
    {
        return $this->hasMany(Negocio::class);
    }
}
