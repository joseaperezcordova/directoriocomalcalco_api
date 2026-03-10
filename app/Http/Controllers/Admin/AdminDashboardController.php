<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller {

    public function index() {
        return view('admin.dashboard.index');
    }

    public function data(Request $request) {
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio)->startOfDay() : Carbon::today()->subDays(6)->startOfDay(); // últimos 7 días incluyendo hoy

        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin)->endOfDay() : Carbon::today()->endOfDay();
        /*
          |--------------------------------------------------------------------------
          | TOTAL GENERAL
          |--------------------------------------------------------------------------
         */
        $ventasTotalGeneral = DB::table('ventas')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total');

        $ticketsTotalGeneral = DB::table('ventas')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->count();

        /*
          |--------------------------------------------------------------------------
          | TOP 5 PRODUCTOS (SIN CORTESÍAS)
          |--------------------------------------------------------------------------
         */
        $productosTop = DB::table('venta_detalles as vd')
                ->join('ventas as v', 'v.id', '=', 'vd.id_venta')
                ->join('productos as p', 'p.id', '=', 'vd.id_producto')
                ->whereBetween('v.created_at', [$fechaInicio, $fechaFin])
                ->where('vd.es_cortesia', 0)
                ->select(
                        'p.nombre',
                        DB::raw('SUM(vd.cantidad) as total_vendidos')
                )
                ->groupBy('p.nombre')
                ->orderByDesc('total_vendidos')
                ->limit(5)
                ->get();

        /*
          |--------------------------------------------------------------------------
          | VENTAS POR PUNTO DE VENTA
          |--------------------------------------------------------------------------
         */
        $ventasPorPunto = DB::table('ventas as v')
                ->join('puntos_venta as pv', 'pv.id', '=', 'v.id_punto_venta')
                ->whereBetween('v.created_at', [$fechaInicio, $fechaFin])
                ->select(
                        'pv.nombre',
                        DB::raw('SUM(v.total) as total_ventas')
                )
                ->groupBy('pv.nombre')
                ->orderByDesc('total_ventas')
                ->get();

        /*
          |--------------------------------------------------------------------------
          | CANTIDAD DE CORTESÍAS POR PUNTO
          |--------------------------------------------------------------------------
         */
        $cortesiasPorPunto = DB::table('venta_detalles as vd')
                ->join('ventas as v', 'v.id', '=', 'vd.id_venta')
                ->join('puntos_venta as pv', 'pv.id', '=', 'v.id_punto_venta')
                ->whereBetween('v.created_at', [$fechaInicio, $fechaFin])
                ->where('vd.es_cortesia', 1)
                ->select(
                        'pv.nombre',
                        DB::raw('SUM(vd.cantidad) as total_cortesias')
                )
                ->groupBy('pv.nombre')
                ->get();

        return response()->json([
                    'ventas_total_general' => $ventasTotalGeneral,
                    'tickets_total_general' => $ticketsTotalGeneral,
                    'productos_top' => $productosTop,
                    'ventas_por_punto' => $ventasPorPunto,
                    'cortesias_por_punto' => $cortesiasPorPunto
        ]);
    }

}
