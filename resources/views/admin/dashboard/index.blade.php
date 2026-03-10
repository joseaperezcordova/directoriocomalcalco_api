@extends('layouts.sbadmin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0"><i class="fas fa-chart-line"></i> Dashboard General</h1>
    </div>

    <!-- FILTRO FECHAS -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    <label>Fecha Inicio</label>
                    <input type="date" id="fecha_inicio" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Fecha Fin</label>
                    <input type="date" id="fecha_fin" class="form-control">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="btnFiltrar">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- INDICADORES -->
    <div class="row">

        <div class="col-md-6">
            <div class="card border-left-success shadow mb-4">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Ventas Totales
                    </div>
                    <div class="h5 font-weight-bold" id="ventas_total">$0.00</div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-left-info shadow mb-4">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Total Tickets
                    </div>
                    <div class="h5 font-weight-bold" id="tickets_total">0</div>
                </div>
            </div>
        </div>

    </div>

    <!-- GRÁFICAS -->
    <div class="row">

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">Ventas por Punto de Venta</div>
                <div class="card-body">
                    <canvas id="graficaPuntos"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">Cortesías por Punto (Cantidad)</div>
                <div class="card-body">
                    <canvas id="graficaCortesias"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- TOP PRODUCTOS -->
    <div class="card shadow">
        <div class="card-header">Top 5 Productos Vendidos</div>
        <div class="card-body">
            <ul id="productos_top" class="list-group"></ul>
        </div>
    </div>

</div>

@endsection

@include('admin.dashboard.scripts')