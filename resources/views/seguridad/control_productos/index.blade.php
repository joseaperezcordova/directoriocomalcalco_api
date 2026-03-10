@extends('layouts.sbadmin')

@section('title','Control de Productos - Seguridad')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0">Control de Productos</h1>

        <button class="btn btn-primary btn-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#modalRecepcion">
            <i class="fas fa-plus me-1"></i> Nuevo
        </button>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table id="tabla" class="table table-sm table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad Recibida</th>
                        <th>Cantidad Entregado</th>
                        <th>Restante</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Modal Recepción --}}
@include('seguridad.control_productos.modals.recepcion')

{{-- Modal Entrega --}}
@include('seguridad.control_productos.modals.entrega')

{{-- Toast --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toastSuccess" class="toast align-items-center text-bg-success border-0 hide" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                Operación realizada correctamente
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@include('seguridad.control_productos.scripts')
@endsection
