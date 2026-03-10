<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncargadoInventarioController extends Controller {

    public function index() {
        return view('encargado.inventario.index');
    }

    public function data() {
        $idPropio = auth()->user()->id_punto_venta;

        // Obtener todos los puntos de venta reales
        $puntos = DB::table('puntos_venta')
                ->where('id', $idPropio)
                ->orderBy('nombre')
                ->pluck('nombre')
                ->toArray();

        // Construir SELECT dinámico
        $select = "i.id_producto, p.nombre";

        foreach ($puntos as $punto) {
            $select .= ",
            SUM(CASE WHEN pv.nombre = '$punto'
                     THEN i.cantidad ELSE 0 END) AS `$punto`";
        }

        // Consulta pivot con join a puntos_venta
        $inventario = DB::table('inventario as i')
                ->join('puntos_venta as pv', 'i.id_punto_venta', '=', 'pv.id')
                ->join('productos as p', 'i.id_producto', '=', 'p.id')
                ->selectRaw($select)
                ->where('i.id_punto_venta', $idPropio)
                ->groupBy('i.id_producto', 'p.nombre')
                ->get();

        return response()->json([
                    'data' => $inventario,
                    'puntos' => $puntos
        ]);
    }

}
