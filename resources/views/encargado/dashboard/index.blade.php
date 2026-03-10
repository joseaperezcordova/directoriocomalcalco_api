@extends('layouts.sbadmin')

@section('title', 'Dashboard Encargado')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0">Dashboard de Encargado</h1>
    </div>
    <!-- Cards resumen -->
    <div class="row">

        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5>Productos Recibidos Hoy</h5>
                    <h2 id ="productosRecibidosHoy">0</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">Recepción</span>
                    <div class="small text-white"><i class="fas fa-box-open"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h5>Productos Entregados</h5>
                    <h2 id="productosEntregadosHoy">0</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">Al Encargado</span>
                    <div class="small text-white"><i class="fas fa-dolly"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h5>Pendientes de Entregar</h5>
                    <h2 id="pendientesEntrega">0</h2>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('encargado.control-productos') }}">
                        Ver pendientes
                    </a>
                    <div class="small text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de movimientos recientes -->
    <div class="card mb-4" >
        <div class="card-header">
            <i class="fas fa-clipboard-list me-1"></i>
            Movimientos Recientes
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover" id="tablaMovimientos">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
@section('scripts')
@include('encargado.dashboard.scripts')
@endsection
