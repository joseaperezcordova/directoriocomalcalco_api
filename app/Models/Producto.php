<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model {

    protected $table = 'productos';
    protected $fillable = ['nombre', 'precio', 'minimo', 'stock'];

    public function stocks() {
        return $this->hasMany(Inventario::class, 'id_producto');
    }

}
