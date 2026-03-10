<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;

class EncargadoVentasController extends Controller {

    public function index() {
        // ⚠️ NO se envían productos aquí
        return view('encargado.ventas.index');
    }

    public function data(Request $req) {
        $idPuntoVenta = Auth::user()->id_punto_venta;
        $ventas = Venta::with('vendedor')
                ->when($req->fecha_inicio, fn($q) =>
                        $q->whereDate('created_at', '>=', $req->fecha_inicio))
                ->when($req->fecha_fin, fn($q) =>
                        $q->whereDate('created_at', '<=', $req->fecha_fin))
                ->when($req->buscar, fn($q) =>
                        $q->where('folio', 'LIKE', "%{$req->buscar}%")
                        ->orWhereHas('vendedor', fn($q2) =>
                                $q2->where('name', 'LIKE', "%{$req->buscar}%")
                        )
                )
                ->where('id_punto_venta', $idPuntoVenta)
                ->orderBy('id', 'DESC')
                ->get();

        return response()->json([
                    'ventas' => $ventas->map(function ($v) {
                        return [
                    'id' => $v->id,
                    'folio' => $v->folio,
                    'fecha' => $v->created_at->format('d/m/Y H:i'),
                    'usuario' => $v->vendedor->name,
                    'total' => $v->total,
                    'pago' => ucfirst($v->forma_pago),
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
