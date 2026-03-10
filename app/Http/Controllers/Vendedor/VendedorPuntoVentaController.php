<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendedorPuntoVentaController extends Controller {

    public function index() {
        return view('vendedor.punto_venta.index');
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

            $inventario = DB::table('inventario')
                ->where('id_producto', $item['id'])
                ->where('id_punto_venta', $idPuntoVenta)
                ->lockForUpdate()
                ->first();

            if (!$inventario) {
                throw new \Exception("Producto no existe en inventario.");
            }

            if ($item['cantidad'] > $inventario->cantidad) {
                throw new \Exception("Stock insuficiente para {$item['nombre']}");
            }

            // 🔥 Si es cortesía el subtotal es 0
            if (!isset($item['es_cortesia']) || $item['es_cortesia'] == 0) {
                $total += $item['cantidad'] * $item['precio'];
            }
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

        foreach ($request->carrito as $item) {

            $esCortesia = isset($item['es_cortesia']) ? $item['es_cortesia'] : 0;

            $precioUnitario = $esCortesia ? 0 : $item['precio'];
            $subtotal = $esCortesia ? 0 : $item['cantidad'] * $item['precio'];

            DB::table('venta_detalles')->insert([
                'id_venta' => $ventaId,
                'id_producto' => $item['id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $precioUnitario,
                'subtotal' => $subtotal,
                'es_cortesia' => $esCortesia,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('inventario')
                ->where('id_producto', $item['id'])
                ->where('id_punto_venta', $idPuntoVenta)
                ->decrement('cantidad', $item['cantidad']);
        }

        DB::commit();

        return response()->json([
            'success' => true
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Error al procesar la venta'
        ], 500);
    }
}
}
