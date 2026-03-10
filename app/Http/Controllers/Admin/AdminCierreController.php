<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AdminCierreController extends Controller {

    public function index() {
        return view('admin.cierres.index');
    }

    public function data(Request $request) {
        $cierres = DB::table('cierres as c')
                ->join('users as u', 'u.id', '=', 'c.id_usuario')
                ->join('puntos_venta as p', 'p.id', '=', 'c.id_punto_venta')
                ->select(
                        'c.id',
                        'c.fecha',
                        'u.name as usuario',
                        'p.nombre as punto',
                        'c.total_efectivo_sistema',
                        'c.total_tarjeta_sistema',
                        'c.total_general_sistema',
                        'c.efectivo_contado'
                )
                ->orderBy('c.fecha', 'desc')
                ->get();

        return response()->json([
                    'data' => $cierres
        ]);
    }

    public function detalle($id) {
        $cierre = DB::table('cierres')->where('id', $id)->first();

        $detalles = DB::table('cierre_detalles as d')
                ->join('productos as p', 'p.id', '=', 'd.id_producto')
                ->where('d.id_cierre', $id)
                ->select(
                        'p.nombre',
                        'd.inicial',
                        'd.entradas',
                        'd.vendido',
                        'd.cortesias',
                        'd.final_sistema',
                        'd.final_fisico',
                        'd.diferencia'
                )
                ->get();

        $html = view('admin.cierres.detalle', compact('cierre', 'detalles'))->render();

        return response()->json(['html' => $html]);
    }

}
