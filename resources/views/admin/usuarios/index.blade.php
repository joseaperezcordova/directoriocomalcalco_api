@extends('layouts.sbadmin')

@section('title', 'Usuarios')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0">Usuarios</h1>
        <button class="btn btn-primary btn-sm" id="btnNuevoUsuario">
            <i class="fas fa-plus me-1"></i> Nuevo
        </button>
    </div>
    <div id="contenedorTabla"></div>
</div>
@include('admin.usuarios.modals.usuario')
@endsection

@section('scripts')
@include('admin.usuarios.scripts')
@endsection

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMensaje">
                Usuario guardado correctamente
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
