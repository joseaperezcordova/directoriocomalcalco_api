<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBitacoraController extends Controller {

    public function index() {
        return view('admin.bitacora.index');
    }

    public function data() {
        $bitacora = DB::table('bitacora_producto as b')
                ->join('productos as p', 'b.id_producto', '=', 'p.id')
                ->join('users as u', 'b.id_usuario', '=', 'u.id')
                ->leftJoin('puntos_venta as pvo', 'b.id_punto_venta_origen', '=', 'pvo.id')
                ->leftJoin('puntos_venta as pvd', 'b.id_punto_venta_destino', '=', 'pvd.id')
                ->select(
                        'b.id',
                        'b.accion',
                        'p.nombre as producto',
                        'b.cantidad',
                        'b.antes',
                        'b.despues',
                        'pvo.nombre as punto_origen',
                        'pvd.nombre as punto_destino',
                        'u.name as usuario',
                        DB::raw("DATE_FORMAT(b.created_at, '%d/%m/%Y %H:%i') as fecha")
                )
                ->orderBy('b.id', 'desc')
                ->get();

        return response()->json([
                    'data' => $bitacora
        ]);
    }

}
