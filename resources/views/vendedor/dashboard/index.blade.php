@extends('layouts.sbadmin')

@section('title', 'Dashboard Vendedor')

@section('content')
<div class="container-fluid px-4">

    <h1 class="mt-4">Dashboard Vendedor</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">
            Bienvenido {{ $usuario->name }}
        </li>
    </ol>

    <!-- Cards resumen -->
    <div class="row">

        <!-- Total vendido hoy -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h6>Total Vendido Hoy</h6>
                    <h2>${{ number_format($ventasHoy, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Número de ventas -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h6>Número de Ventas</h6>
                    <h2>{{ $totalVentas }}</h2>
                </div>
            </div>
        </div>

        <!-- Productos vendidos -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h6>Productos Vendidos</h6>
                    <h2>{{ $productosVendidos }}</h2>
                </div>
            </div>
        </div>

        <!-- Cortesías -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <h6>Cortesías</h6>
                    <h2>{{ $totalCortesias }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Métodos de pago -->
    <div class="row">

        <div class="col-xl-6">
            <div class="card border-left-success shadow mb-4">
                <div class="card-body">
                    <h5 class="text-success">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Efectivo
                    </h5>
                    <h3>${{ number_format($totalEfectivo, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card border-left-primary shadow mb-4">
                <div class="card-body">
                    <h5 class="text-primary">
                        <i class="fas fa-credit-card me-2"></i>
                        Tarjeta
                    </h5>
                    <h3>${{ number_format($totalTarjeta, 2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Gráfica ventas por hora -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-line me-1"></i>
            Ventas por Hora
        </div>
        <div class="card-body">
            <canvas id="ventasHoraChart" width="100%" height="30"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
@include('vendedor.dashboard.scripts')
@endpush