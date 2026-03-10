<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;

class VendedorVentasController extends Controller {

    public function index() {
        // ⚠️ NO se envían productos aquí
        return view('vendedor.ventas.index');
    }

    public function data(Request $req) {
        $idPuntoVenta = Auth::user()->id_punto_venta;

        $ventas = Venta::with('vendedor')
                ->where('id_punto_venta', $idPuntoVenta)
                ->orderBy('id', 'DESC')
                ->get();

        return response()->json([
                    'ventas' => $ventas->map(function ($v) {

                        // 🔥 VALIDACIÓN CORRECTA
                        $tieneCortesia = $v->detalles()
                                ->where('es_cortesia', 1)
                                ->exists();

                        return [
                    'id' => $v->id,
                    'fecha' => $v->created_at->format('d/m/Y H:i'),
                    'usuario' => $v->vendedor->name ?? '---',
                    'pago' => ucfirst($v->forma_pago),
                    'total' => (float) $v->total,
                    'tiene_cortesia' => $tieneCortesia
                        ];
                    })
        ]);
    }

    public function detalle(Request $req) {
        $venta = Venta::with('detalles.producto')->find($req->id);

        $html = view('vendedor.ventas.detalle', compact('venta'))->render();

        return response()->json(['html' => $html]);
    }

}
