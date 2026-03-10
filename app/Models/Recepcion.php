<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    protected $table = 'recepciones';
    protected $guarded = [];

    public function detalles()
    {
        return $this->hasMany(RecepcionDetalle::class);
    }
}
