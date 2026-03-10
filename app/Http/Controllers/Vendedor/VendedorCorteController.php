<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class VendedorCorteController extends Controller {

    public function index() {
        $user = auth()->user();
        $idPunto = $user->id_punto_venta;

        $puntoVenta = DB::table('puntos_venta')
                ->where('id', $idPunto)
                ->first();

        $productosBase = DB::table('inventario as i')
                ->join('productos as p', 'p.id', '=', 'i.id_producto')
                ->where('i.id_punto_venta', $idPunto)
                ->select('p.id', 'p.nombre', 'i.cantidad')
                ->get();

        $productos = [];

        foreach ($productosBase as $p) {

            $existenciaActual = $p->cantidad;

            $entradas = DB::table('entrega_detalles')
                    ->where('id_producto', $p->id)
                    ->where('id_punto_venta', $idPunto)
                    ->whereDate('created_at', now())
                    ->sum('cantidad_entregada');

            // 🔥 VENDIDO NORMAL
            $vendido = DB::table('venta_detalles as d')
                    ->join('ventas as v', 'v.id', '=', 'd.id_venta')
                    ->where('v.id_punto_venta', $idPunto)
                    ->whereDate('v.created_at', now())
                    ->where('d.id_producto', $p->id)
                    ->where('d.es_cortesia', 0)
                    ->sum('d.cantidad');

            // 🔥 CORTESÍAS
            $cortesias = DB::table('venta_detalles as d')
                    ->join('ventas as v', 'v.id', '=', 'd.id_venta')
                    ->where('v.id_punto_venta', $idPunto)
                    ->whereDate('v.created_at', now())
                    ->where('d.id_producto', $p->id)
                    ->where('d.es_cortesia', 1)
                    ->sum('d.cantidad');

            $inicial = $existenciaActual - $entradas + $vendido + $cortesias;

            $productos[] = (object) [
                        'id' => $p->id,
                        'nombre' => $p->nombre,
                        'inicial' => $inicial,
                        'entradas' => $entradas,
                        'vendido' => $vendido,
                        'cortesias' => $cortesias,
                        'final_sistema' => $existenciaActual
            ];
        }

        // 🔥 TOTALES SOLO VENTA REAL
        $efectivo = DB::table('ventas')
                ->where('id_punto_venta', $idPunto)
                ->whereDate('created_at', now())
                ->where('forma_pago', 'efectivo')
                ->sum('total');

        $tarjeta = DB::table('ventas')
                ->where('id_punto_venta', $idPunto)
                ->whereDate('created_at', now())
                ->where('forma_pago', 'tarjeta')
                ->sum('total');

        $totales = [
            'efectivo' => $efectivo,
            'tarjeta' => $tarjeta,
            'total' => $efectivo + $tarjeta
        ];

        return view('vendedor.corte.index', compact(
                        'productos',
                        'puntoVenta',
                        'totales'
        ));
    }

    public function store(Request $request) {
        DB::beginTransaction();

        try {

            $idCorte = DB::table('cierres')->insertGetId([
                'fecha' => now(),
                'id_usuario' => auth()->id(),
                'id_punto_venta' => $request->id_punto_venta,
                'total_efectivo_sistema' => $request->total_efectivo_sistema,
                'total_tarjeta_sistema' => $request->total_tarjeta_sistema,
                'total_general_sistema' => $request->total_general_sistema,
                'efectivo_contado' => $request->efectivo_contado,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->productos as $idProducto => $data) {

                DB::table('cierre_detalles')->insert([
                    'id_cierre' => $idCorte,
                    'id_producto' => $idProducto,
                    'inicial' => $data['inicial'],
                    'entradas' => $data['entradas'],
                    'vendido' => $data['vendido'],
                    'cortesias' => $data['cortesias'],
                    'final_sistema' => $data['final_sistema'],
                    'final_fisico' => $data['final_fisico'],
                    'diferencia' => $data['final_fisico'] - $data['final_sistema'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json(['ok' => true]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                        'error' => 'Error al guardar el corte'
                            ], 500);
        }
    }

}
