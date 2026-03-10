<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EncargadoPuntoVentaController extends Controller {

    public function index() {
        return view('encargado.punto_venta.index');
    }

    public function data() {
        $idPuntoVenta = Auth::user()->id_punto_venta;
        // Obtener productos activos con precio
        $productos = DB::table('inventario as i')
                ->join('productos as p', 'i.id_producto', '=', 'p.id')
                ->select(
                        'p.id',
                        'p.nombre',
                        'p.precio',
                        'i.cantidad'
                )
                ->where('i.id_punto_venta', $idPuntoVenta)
                ->where('i.cantidad', '>', 0)
                ->orderBy('p.nombre')
                ->get();

        return response()->json([
                    'success' => true,
                    'productos' => $productos
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'carrito' => 'required|array|min:1',
            'forma_pago' => 'required|string'
        ]);

        $idPuntoVenta = Auth::user()->id_punto_venta;

        DB::beginTransaction();

        try {

            $total = 0;

            foreach ($request->carrito as $item) {

                // 🔒 Bloquea fila de inventario
                $inventario = DB::table('inventario')
                        ->where('id_producto', $item['id'])
                        ->where('id_punto_venta', $idPuntoVenta)
                        ->lockForUpdate()
                        ->first();

                if (!$inventario) {
                    throw new \Exception("Producto no existe en inventario.");
                }

                if ($item['cantidad'] > $inventario->cantidad) {
                    throw new \Exception("Stock insuficiente para {$item['id']}");
                }

                $total += $item['cantidad'] * $item['precio'];
            }

            // Crear venta
            $ventaId = DB::table('ventas')->insertGetId([
                'id_usuario' => auth()->id(),
                'id_punto_venta' => $idPuntoVenta,
                'forma_pago' => $request->forma_pago,
                'total' => $total,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Insertar detalle y descontar inventario
            foreach ($request->carrito as $item) {

                DB::table('venta_detalles')->insert([
                    'id_venta' => $ventaId,
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['cantidad'] * $item['precio'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('inventario')
                        ->where('id_producto', $item['id'])
                        ->where('id_punto_venta', $idPuntoVenta)
                        ->decrement('cantidad', $item['cantidad']);
            }

            DB::commit();

            return response()->json(
                            [
                                'success' => true
//                                ,'productos' => Producto::select('id', 'cantidad')->get()
                            ]
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar la venta'
                            ], 500);
        }
    }

}
