<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SeguridadDashboardController extends Controller {

    public function index() {
        return view('seguridad.dashboard.index');
    }

    public function data() {
        $puntoSeguridad = auth()->user()->id_punto_venta;

        /*
          |--------------------------------------------------------------------------
          | 1️⃣ LISTA DE ÚLTIMOS MOVIMIENTOS
          |--------------------------------------------------------------------------
         */
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
                ->where(function ($q) use ($puntoSeguridad) {
                    $q->where('m.id_punto_venta_origen', $puntoSeguridad)
                    ->orWhere('m.id_punto_venta_destino', $puntoSeguridad);
                })
                ->orderByDesc('m.id')
                ->limit(20) // solo últimos 100 para no sobrecargar
                ->get();

        /*
          |--------------------------------------------------------------------------
          | 2️⃣ PRODUCTOS RECIBIDOS HOY (Destino = Seguridad)
          |--------------------------------------------------------------------------
         */
        $recibidosHoy = DB::table('movimientos_detalle')
                ->where('id_punto_venta_destino', $puntoSeguridad)
                ->whereDate('created_at', now()->toDateString())
                ->sum('cantidad');

        /*
          |--------------------------------------------------------------------------
          | 3️⃣ PRODUCTOS ENTREGADOS HOY (Origen = Seguridad)
          |--------------------------------------------------------------------------
         */
        $entregadosHoy = DB::table('movimientos_detalle')
                ->where('id_punto_venta_origen', $puntoSeguridad)
                ->whereDate('created_at', now()->toDateString())
                ->sum('cantidad');

        /*
          |--------------------------------------------------------------------------
          | 4️⃣ PRODUCTOS PENDIENTES DE ENTREGA
          |--------------------------------------------------------------------------
          | Asumimos que los pendientes están marcados con estado = 'pendiente'
          |--------------------------------------------------------------------------
         */
        $sub = DB::table('recepcion_detalles as r')
                ->leftJoin('entrega_detalles as e', 'r.id', '=', 'e.id_recepcion_detalle')
                ->where('r.id_punto_venta', $puntoSeguridad)
                ->groupBy('r.id', 'r.cantidad_recibida')
                ->selectRaw('
        r.id,
        (r.cantidad_recibida - IFNULL(SUM(e.cantidad_entregada),0)) as pendiente
    ');

        $pendientes = DB::query()
                ->fromSub($sub, 't')
                ->where('pendiente', '>', 0)
                ->sum('pendiente');

        return response()->json([
                    'data' => $movimientos,
                    'totales' => [
                        'recibidos_hoy' => $recibidosHoy,
                        'entregados_hoy' => $entregadosHoy,
                        'pendientes' => $pendientes
                    ]
        ]);
    }

}
