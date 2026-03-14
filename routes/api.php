<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NegocioPublicoController;
use App\Http\Controllers\Api\NegocioCapturaController;

// =====================================================
// RUTAS PÚBLICAS (sin autenticación)
// =====================================================

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);

// Directorio público
Route::get('/categorias', [NegocioPublicoController::class, 'categorias']);
Route::get('/negocios', [NegocioPublicoController::class, 'index']);
Route::get('/negocios/{id}', [NegocioPublicoController::class, 'show']);

// Prueba rápida
Route::get('/test', fn() => response()->json(['ok' => true, 'proyecto' => 'Directorio Comalcalco']));

// =====================================================
// RUTAS PROTEGIDAS (requieren token)
// =====================================================
Route::middleware('auth.token')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Captura — capturistas y admin
    Route::prefix('captura')->group(function () {
        Route::get('/negocios', [NegocioCapturaController::class, 'index']);
        Route::post('/negocios', [NegocioCapturaController::class, 'store']);
        Route::put('/negocios/{id}', [NegocioCapturaController::class, 'update']);
        Route::post('/negocios/{id}/foto', [NegocioCapturaController::class, 'subirFoto']);
    });

    // Solo admin
    Route::middleware('auth.token:admin')->prefix('admin')->group(function () {
        Route::post('/negocios/{id}/aprobar', [NegocioCapturaController::class, 'aprobar']);
        Route::post('/negocios/{id}/rechazar', [NegocioCapturaController::class, 'rechazar']);
        Route::get('/stats', [NegocioCapturaController::class, 'stats']);
    });
});
