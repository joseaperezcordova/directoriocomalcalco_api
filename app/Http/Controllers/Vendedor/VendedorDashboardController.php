<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendedorDashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $hoy = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | Query base ventas del día del vendedor
        |--------------------------------------------------------------------------
        */
        $ventasQuery = DB::table('ventas')
            ->where('id_usuario', $usuario->id)
            ->whereDate('created_at', $hoy);

        /*
        |--------------------------------------------------------------------------
        | Total vendido hoy (SIN contar cortesías)
        |--------------------------------------------------------------------------
        */
        $ventasHoy = DB::table('venta_detalles as vd')
            ->join('ventas as v', 'v.id', '=', 'vd.id_venta')
            ->where('v.id_usuario', $usuario->id)
            ->whereDate('v.created_at', $hoy)
            ->where('vd.es_cortesia', 0)
            ->sum('vd.subtotal');

        /*
        |--------------------------------------------------------------------------
        | Número de tickets del día
        |--------------------------------------------------------------------------
        */
        $totalVentas = (clone $ventasQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Total productos vendidos (sin cortesía)
        |--------------------------------------------------------------------------
        */
        $productosVendidos = DB::table('venta_detalles as vd')
            ->join('ventas as v', 'v.id', '=', 'vd.id_venta')
            ->where('v.id_usuario', $usuario->id)
            ->whereDate('v.created_at', $hoy)
            ->where('vd.es_cortesia', 0)
            ->sum('vd.cantidad');

        /*
        |--------------------------------------------------------------------------
        | Total productos en cortesía
        |--------------------------------------------------------------------------
        */
        $totalCortesias = DB::table('venta_detalles as vd')
            ->join('ventas as v', 'v.id', '=', 'vd.id_venta')
            ->where('v.id_usuario', $usuario->id)
            ->whereDate('v.created_at', $hoy)
            ->where('vd.es_cortesia', 1)
            ->sum('vd.cantidad');

        /*
        |--------------------------------------------------------------------------
        | Métodos de pago
        |--------------------------------------------------------------------------
        */
        $totalEfectivo = (clone $ventasQuery)
            ->where('forma_pago', 'efectivo')
            ->sum('total');

        $totalTarjeta = (clone $ventasQuery)
            ->where('forma_pago', 'tarjeta')
            ->sum('total');

        /*
        |--------------------------------------------------------------------------
        | Ventas por hora (sin cortesía)
        |--------------------------------------------------------------------------
        */
        $ventasPorHora = DB::table('venta_detalles as vd')
            ->join('ventas as v', 'v.id', '=', 'vd.id_venta')
            ->select(
                DB::raw('HOUR(v.created_at) as hora'),
                DB::raw('SUM(vd.subtotal) as total')
            )
            ->where('v.id_usuario', $usuario->id)
            ->whereDate('v.created_at', $hoy)
            ->where('vd.es_cortesia', 0)
            ->groupBy(DB::raw('HOUR(v.created_at)'))
            ->orderBy('hora')
            ->get();

        $labelsHoras = [];
        $dataHoras = [];

        foreach ($ventasPorHora as $vh) {
            $labelsHoras[] = str_pad($vh->hora, 2, '0', STR_PAD_LEFT) . ':00';
            $dataHoras[] = (float) $vh->total;
        }

        return view('vendedor.dashboard.index', compact(
            'usuario',
            'ventasHoy',
            'totalVentas',
            'totalCortesias',
            'productosVendidos',
            'totalEfectivo',
            'totalTarjeta',
            'labelsHoras',
            'dataHoras'
        ));
    }
}