<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\RecepcionDetalle;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoDetalle;
use App\Models\Inventario;
use App\Models\EntregaDetalle;
use App\Models\User;

class SeguridadControlProductosController extends Controller {

    /**
     * Listado principal (como tu imagen)
     */
    public function index() {
        return view('seguridad.control_productos.index');
    }

    public function data(Request $request) {
        $idPropio = auth()->user()->id_punto_venta;

        $data = RecepcionDetalle::select(
                        'recepcion_detalles.id',
                        'productos.nombre as producto',
                        'recepcion_detalles.cantidad_recibida',
                        // Total entregado
                        DB::raw("COALESCE(SUM(entrega_detalles.cantidad_entregada),0) as cantidad_entregada"),
                        // Restante
                        DB::raw("recepcion_detalles.cantidad_recibida - COALESCE(SUM(entrega_detalles.cantidad_entregada),0) as restante"),
                        DB::raw("DATE_FORMAT(recepcion_detalles.created_at, '%d/%m/%Y %H:%i') as fecha")
                )
                ->leftJoin('productos', 'productos.id', '=', 'recepcion_detalles.id_producto')
                ->leftJoin('entrega_detalles', 'entrega_detalles.id_recepcion_detalle', '=', 'recepcion_detalles.id')
                ->where('recepcion_detalles.id_punto_venta', $idPropio)
                ->groupBy(
                        'recepcion_detalles.id',
                        'productos.nombre',
                        'recepcion_detalles.cantidad_recibida',
                        'recepcion_detalles.created_at'
                )
                ->get();

        return response()->json([
                    'data' => $data
        ]);
    }

    public function productos() {
        return Producto::select('id', 'nombre')->orderBy('nombre')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'items' => 'required|array|min:1'
        ]);

        $idPropio = auth()->user()->id_punto_venta;

        DB::transaction(function () use ($request, $idPropio) {

            foreach ($request->items as $item) {

                // 3️⃣ Guardar detalle recepción
                RecepcionDetalle::create([
                    'id_producto' => $item['producto_id'],
                    'cantidad_recibida' => $item['cantidad'],
                    'id_punto_venta' => $idPropio
                ]);

                // 4️⃣ Guardar detalle movimiento
                MovimientoDetalle::create([
                    'tipo' => 'ENTRADA',
                    'id_producto' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'id_punto_venta_origen' => null,
                    'id_punto_venta_destino' => $idPropio,
                    'id_usuario' => auth()->id()
                ]);

                $antes_destino = DB::table('inventario')
                                ->where('id_producto', $item['producto_id'])
                                ->where('id_punto_venta', $idPropio)
                                ->value('cantidad') ?? 0;

                $despues_destino = $antes_destino + $item['cantidad'];

                DB::table('bitacora_producto')->insert([
                    'id_usuario' => auth()->id(),
                    'id_producto' => $item['producto_id'],
                    'accion' => "RECEPCION",
                    'cantidad' => $item['cantidad'],
                    'id_punto_venta_origen' => null,
                    'id_punto_venta_destino' => $idPropio,
                    'antes' => $antes_destino,
                    'despues' => $despues_destino
                ]);

                // 5️⃣ Actualizar inventario del almacén
                $inventario = Inventario::where('id_producto', $item['producto_id'])
                        ->where('id_punto_venta', $idPropio)
                        ->lockForUpdate()
                        ->first();

                if ($inventario) {
                    $inventario->increment('cantidad', $item['cantidad']);
                } else {
                    Inventario::create([
                        'id_producto' => $item['producto_id'],
                        'id_punto_venta' => $idPropio,
                        'cantidad' => $item['cantidad']
                    ]);
                }
            }
        });

        return response()->json(['ok' => true]);
    }

    public function entrega(Request $request) {
        $request->validate([
            'recepcion_id' => 'required|integer|exists:recepcion_detalles,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $idPropio = auth()->user()->id_punto_venta;
        $idEncargado = User::where('rol', 'encargado')->value('id_punto_venta');

        DB::beginTransaction();

        try {

            // 🔒 Bloquea recepción
            $recepcion = RecepcionDetalle::lockForUpdate()
                    ->findOrFail($request->recepcion_id);

            // 🔥 Calcular total entregado hasta ahora
            $totalEntregado = EntregaDetalle::where('id_recepcion_detalle', $recepcion->id)
                    ->sum('cantidad_entregada');

            $restante = $recepcion->cantidad_recibida - $totalEntregado;

            if ($request->cantidad > $restante) {
                DB::rollBack();
                return response()->json([
                            'error' => 'Cantidad mayor a lo disponible'
                                ], 422);
            }

            if ($restante <= 0) {
                DB::rollBack();
                return response()->json([
                            'error' => 'No hay cantidad disponible para entregar'
                                ], 422);
            }

            /*
              ===============================
              🔥 VALIDAR INVENTARIO ORIGEN
              ===============================
             */

            $inventarioOrigen = Inventario::lockForUpdate()->where([
                        'id_producto' => $recepcion->id_producto,
                        'id_punto_venta' => $idPropio
                    ])->first();

            $antes_origen = DB::table('inventario')
                            ->where('id_producto', $recepcion->id_producto)
                            ->where('id_punto_venta', $idPropio)
                            ->value('cantidad') ?? 0;

            $despues_origen = $antes_origen - $request->cantidad;

            DB::table('bitacora_producto')->insert([
                'id_usuario' => auth()->id(),
                'id_producto' => $recepcion->id_producto,
                'accion' => "ENTREGA",
                'cantidad' => $request->cantidad,
                'id_punto_venta_origen' => $idPropio,
                'id_punto_venta_destino' => $idEncargado,
                'antes' => $antes_origen,
                'despues' => $despues_origen
            ]);
            if (!$inventarioOrigen || $inventarioOrigen->cantidad < $request->cantidad) {

                DB::rollBack();

                return response()->json([
                            'error' => 'Stock insuficiente'
                                ], 422);
            }

            /*
              ===============================
              ✅ GUARDAR ENTREGA
              ===============================
             */


            EntregaDetalle::create([
                'id_recepcion_detalle' => $recepcion->id,
                'id_producto' => $recepcion->id_producto,
                'cantidad_entregada' => $request->cantidad,
                'id_punto_venta' => $idEncargado,
                'id_usuario' => auth()->id()
            ]);

            MovimientoDetalle::create([
                'tipo' => 'ENTREGA',
                'id_producto' => $recepcion->id_producto,
                'cantidad' => $request->cantidad,
                'id_punto_venta_origen' => $idPropio,
                'id_punto_venta_destino' => $idEncargado,
                'id_usuario' => auth()->id()
            ]);

            /*
              ===============================
              🔻 RESTAR INVENTARIO ORIGEN
              ===============================
             */

            $inventarioOrigen->cantidad -= $request->cantidad;
            $inventarioOrigen->save();

            /*
              ===============================
              🔺 SUMAR INVENTARIO DESTINO
              ===============================
             */

            $inventarioDestino = Inventario::lockForUpdate()->firstOrNew([
                'id_producto' => $recepcion->id_producto,
                'id_punto_venta' => $idEncargado
            ]);
            $antes_destino = DB::table('inventario')
                            ->where('id_producto', $recepcion->id_producto)
                            ->where('id_punto_venta', $idEncargado)
                            ->value('cantidad') ?? 0;

            $despues_destino = $antes_destino + $request->cantidad;

            DB::table('bitacora_producto')->insert([
                'id_usuario' => auth()->id(),
                'id_producto' => $recepcion->id_producto,
                'accion' => "RECEPCION",
                'cantidad' => $request->cantidad,
                'id_punto_venta_origen' => $idPropio,
                'id_punto_venta_destino' => $idEncargado,
                'antes' => $antes_destino,
                'despues' => $despues_destino
            ]);
            if (!$inventarioDestino->exists) {
                $inventarioDestino->cantidad = 0;
            }

            $inventarioDestino->cantidad += $request->cantidad;
            $inventarioDestino->save();

            DB::commit();

            return response()->json([
                        'success' => true
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                        'error' => 'Error al guardar la entrega',
                        'detalle' => 'Ocurrió un error inesperado'
                            ], 500);
        }
    }

}
