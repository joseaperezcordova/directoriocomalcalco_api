<?php

use Illuminate\Support\Facades\Route;
// Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductoController;
use App\Http\Controllers\Admin\AdminUsuariosController;
use App\Http\Controllers\Admin\AdminMovimientosController;
use App\Http\Controllers\Admin\AdminInventarioController;
use App\Http\Controllers\Admin\AdminVentasController;
use App\Http\Controllers\Admin\AdminBitacoraController;
use App\Http\Controllers\Admin\AdminCierreController;
use App\Http\Controllers\Seguridad\SeguridadDashboardController;
use App\Http\Controllers\Seguridad\SeguridadControlProductosController;
use App\Http\Controllers\Seguridad\SeguridadInventarioController;
use App\Http\Controllers\Seguridad\SeguridadMovimientosController;
use App\Http\Controllers\Encargado\EncargadoDashboardController;
use App\Http\Controllers\Encargado\EncargadoControlProductosController;
use App\Http\Controllers\Encargado\EncargadoMovimientosController;
use App\Http\Controllers\Encargado\EncargadoInventarioController;
use App\Http\Controllers\Encargado\EncargadoPuntoVentaController;
use App\Http\Controllers\Encargado\EncargadoVentasController;
use App\Http\Controllers\Vendedor\VendedorDashboardController;
use App\Http\Controllers\Vendedor\VendedorInventarioController;
use App\Http\Controllers\Vendedor\VendedorPuntoVentaController;
use App\Http\Controllers\Vendedor\VendedorVentasController;
use App\Http\Controllers\Vendedor\VendedorCorteController;

/*
  |--------------------------------------------------------------------------
  | RUTA PRINCIPAL → LOGIN
  |--------------------------------------------------------------------------
 */
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'show'])
        ->name('login');

Route::post('/login', [LoginController::class, 'login'])
        ->name('login.post');

/*
  |--------------------------------------------------------------------------
  | DASHBOARDS POR ROL
  |--------------------------------------------------------------------------
 */

Route::middleware(['auth', 'role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // DASHBOARD
            Route::get('dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
            Route::get('dashboard-data', [AdminDashboardController::class, 'data'])
            ->name('dashboard.data');

            // PRODUCTOS
            Route::get('productos', [AdminProductoController::class, 'index'])
            ->name('productos');

            Route::get('productos/tabla', [AdminProductoController::class, 'tabla'])
            ->name('productos.tabla');

            Route::post('productos', [AdminProductoController::class, 'store'])
            ->name('productos.store');

            Route::put('productos/{id}', [AdminProductoController::class, 'update'])
            ->name('productos.update');

            //USUARIOS
            Route::get('usuarios', [AdminUsuariosController::class, 'index'])
            ->name('usuarios');

            Route::get('usuarios/tabla', [AdminUsuariosController::class, 'tabla'])
            ->name('usuarios.tabla');

            Route::post('usuarios', [AdminUsuariosController::class, 'store'])
            ->name('usuarios.store');

            Route::put('usuarios/{id}', [AdminUsuariosController::class, 'update'])
            ->name('usuarios.update');

            //MOVIMIENTOS
            Route::get('movimientos', [AdminMovimientosController::class, 'index'])
            ->name('movimientos');

            Route::get('movimientos-data', [AdminMovimientosController::class, 'data'])
            ->name('movimientos.data');

            //INVENTARIO
            Route::get('inventario-data', [AdminInventarioController::class, 'data'])
            ->name('inventario.data');

            Route::get('inventario', [AdminInventarioController::class, 'index'])
            ->name('inventario');

            Route::get('inventario/tabla', [AdminInventarioController::class, 'tabla'])
            ->name('inventario.tabla');

            //BITACORA
            Route::get('bitacora-data', [AdminBitacoraController::class, 'data'])
            ->name('bitacora.data');

            Route::get('bitacora', [AdminBitacoraController::class, 'index'])
            ->name('bitacora');

            //VENTAS
            Route::get('ventas', [AdminVentasController::class, 'index'])
            ->name('ventas');
            Route::get('ventas-data', [AdminVentasController::class, 'data'])
            ->name('ventas.data');
            Route::get('ventas-detalle', [AdminVentasController::class, 'detalle'])
            ->name('ventas.detalle');

            //LISTA CIERRES
            Route::get('cierres', [AdminCierreController::class, 'index'])
            ->name('cierres');

            Route::get('cierres/data', [AdminCierreController::class, 'data'])
            ->name('cierres.data');

            Route::get('cierres/detalle/{id}', [AdminCierreController::class, 'detalle'])
            ->name('cierres.detalle');
        });

// SEGURIDAD
Route::middleware(['auth', 'role:seguridad'])
        ->prefix('seguridad')
        ->name('seguridad.')
        ->group(function () {
            //DASHBOARD
            Route::get('dashboard', [SeguridadDashboardController::class, 'index'])
            ->name('dashboard');
            Route::get('dashboard-data', [SeguridadDashboardController::class, 'data'])
            ->name('dashboard.data');

            //CONTROL PRODUCTOS
            Route::get('control-productos', [SeguridadControlProductosController::class, 'index'])
            ->name('control-productos');

            Route::post('control-productos', [SeguridadControlProductosController::class, 'store'])
            ->name('control-productos.store');

            Route::post('control-productos/entrega', [SeguridadControlProductosController::class, 'entrega'])
            ->name('control-productos.entrega');

            Route::get('control-productos/productos', [SeguridadControlProductosController::class, 'productos'])
            ->name('control-productos.productos');

            Route::get('control-productos-data', [SeguridadControlProductosController::class, 'data'])
            ->name('control-productos.data');

            //INVENTARIO
            Route::get('inventario-data', [SeguridadInventarioController::class, 'data'])
            ->name('inventario.data');

            Route::get('inventario', [SeguridadInventarioController::class, 'index'])
            ->name('inventario');

            //MOVIMIENTOS
            Route::get('movimientos', [SeguridadMovimientosController::class, 'index'])
            ->name('movimientos');

            Route::get('movimientos-data', [SeguridadMovimientosController::class, 'data'])
            ->name('movimientos.data');
        });

Route::middleware(['auth', 'role:encargado'])
        ->prefix('encargado')
        ->name('encargado.')
        ->group(function () {
            //DASHBOARD
            Route::get('dashboard', [EncargadoDashboardController::class, 'index'])
            ->name('dashboard');
            Route::get('dashboard-data', [EncargadoDashboardController::class, 'data'])
            ->name('dashboard.data');

            Route::get('control-productos', [EncargadoControlProductosController::class, 'index'])
            ->name('control-productos');

            Route::post('control-productos', [EncargadoControlProductosController::class, 'store'])
            ->name('control-productos.store');

            Route::post('control-productos/entrega', [EncargadoControlProductosController::class, 'entrega'])
            ->name('control-productos.entrega');

            Route::get('control-productos/productos', [EncargadoControlProductosController::class, 'productos'])
            ->name('control-productos.productos');

            Route::get('control-productos-data', [EncargadoControlProductosController::class, 'data'])
            ->name('control-productos.data');

            //MOVIMIENTOS
            Route::get('movimientos', [EncargadoMovimientosController::class, 'index'])
            ->name('movimientos');

            Route::get('movimientos-data', [EncargadoMovimientosController::class, 'data'])
            ->name('movimientos.data');

            //INVENTARIO
            Route::get('inventario-data', [EncargadoInventarioController::class, 'data'])
            ->name('inventario.data');

            Route::get('inventario', [EncargadoInventarioController::class, 'index'])
            ->name('inventario');

            //PUNTO VENTA
            Route::get('punto-venta', [EncargadoPuntoVentaController::class, 'index'])
            ->name('punto-venta');

            Route::get('punto-venta-data', [EncargadoPuntoVentaController::class, 'data'])
            ->name('punto-venta.data');

            Route::post('punto-venta/store', [EncargadoPuntoVentaController::class, 'store'])
            ->name('punto-venta.store');

            //VENTAS
            Route::get('ventas', [EncargadoVentasController::class, 'index'])
            ->name('ventas');

            Route::get('ventas-data', [EncargadoVentasController::class, 'data'])
            ->name('ventas.data');
            Route::get('ventas-detalle', [EncargadoVentasController::class, 'detalle'])
            ->name('ventas.detalle');
        });
// VENDEDOR
Route::middleware(['auth', 'role:vendedor'])
        ->prefix('vendedor')
        ->name('vendedor.')
        ->group(function () {
            //DASHBOARD
            Route::get('dashboard', [VendedorDashboardController::class, 'index'])
            ->name('dashboard');
            Route::get('dashboard-data', [VendedorDashboardController::class, 'data'])
            ->name('dashboard.data');

            //INVENTARIO
            Route::get('inventario', [VendedorInventarioController::class, 'index'])
            ->name('inventario');

            Route::get('inventario-data', [VendedorInventarioController::class, 'data'])
            ->name('inventario.data');

            //PUNTO VENTA
            Route::get('punto-venta', [VendedorPuntoVentaController::class, 'index'])
            ->name('punto-venta');

            Route::get('punto-venta-data', [VendedorPuntoVentaController::class, 'data'])
            ->name('punto-venta.data');

            Route::post('punto-venta/store', [VendedorPuntoVentaController::class, 'store'])
            ->name('punto-venta.store');

            //VENTAS
            Route::get('ventas', [VendedorVentasController::class, 'index'])
            ->name('ventas');

            Route::get('ventas-data', [VendedorVentasController::class, 'data'])
            ->name('ventas.data');
            Route::get('ventas-detalle', [VendedorVentasController::class, 'detalle'])
            ->name('ventas.detalle');

            //CORTE
            Route::get('corte', [VendedorCorteController::class, 'index'])
            ->name('corte');

            Route::post('corte/store', [VendedorCorteController::class, 'store'])
            ->name('corte.store');
        });

/*
  |--------------------------------------------------------------------------
  | LOGOUT
  |--------------------------------------------------------------------------
 */
Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
