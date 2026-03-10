<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeguridadMovimientosController extends Controller {

    public function index() {
        return view('seguridad.movimientos.index');
    }

    public function data() {
        $movimientos = DB::table('movimientos_detalle as m')
                ->join('productos as p', 'm.id_producto', '=', 'p.id')
                ->leftJoin('puntos_venta as pvo', 'm.id_punto_venta_origen', '=', 'pvo.id')
                ->leftJoin('puntos_venta as pvd', 'm.id_punto_venta_destino', '=', 'pvd.id')
                ->join('users as u', 'm.id_usuario', '=', 'u.id')
                ->select(
                        'm.id',
                        'm.tipo',
                        'p.nombre as producto',
                        'm.cantidad',
                        'pvo.nombre as punto_origen',
                        'pvd.nombre as punto_destino',
                        'u.name as usuario',
                        DB::raw("DATE_FORMAT(m.created_at, '%d/%m/%Y %H:%i') as fecha")
                )
                ->where(function ($q) {
                    $idPropio = auth()->user()->id_punto_venta;
                    $q->where('m.id_punto_venta_origen', $idPropio)
                    ->orWhere('m.id_punto_venta_destino', $idPropio);
                })
                ->orderBy('m.id', 'desc')
                ->get();

        return response()->json([
                    'data' => $movimientos
        ]);
    }

}
