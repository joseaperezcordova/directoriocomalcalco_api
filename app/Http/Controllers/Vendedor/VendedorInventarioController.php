<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendedorInventarioController extends Controller {

    public function index() {
        // ⚠️ NO se envían productos aquí
        return view('vendedor.inventario.index');
    }

    public function data() {
        // Obtener todos los puntos de venta reales
        $puntos = DB::table('puntos_venta')
                ->where('id', Auth::user()->id_punto_venta)
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
                ->where('i.id_punto_venta', Auth::user()->id_punto_venta)
                ->groupBy('i.id_producto', 'p.nombre')
                ->get();

        return response()->json([
                    'data' => $inventario,
                    'puntos' => $puntos
        ]);
    }

}
