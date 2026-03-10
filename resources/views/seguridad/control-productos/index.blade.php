@extends('layouts.sbadmin')

@section('title','Control de Productos - Seguridad')

@section('content')
<div class="container-fluid px-4">
    
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0">Control de Recepción y Despacho</h1>

        <button class="btn btn-primary btn-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#modalRecepcion">
            <i class="fas fa-plus me-1"></i> Nuevo
        </button>
    </div>

    {{-- Filtro por fechas --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('seguridad.control-productos.index') }}">
                <div class="row g-2 align-items-end">
                    
                    <div class="col-md-3">
                        <label class="form-label">Desde</label>
                        <input type="date" 
                               name="desde" 
                               class="form-control form-control-sm"
                               value="{{ request('desde') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Hasta</label>
                        <input type="date" 
                               name="hasta" 
                               class="form-control form-control-sm"
                               value="{{ request('hasta') }}">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-search me-1"></i> Filtrar
                        </button>

                        <a href="{{ route('seguridad.control-productos.index') }}" 
                           class="btn btn-light btn-sm">
                            Limpiar
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th class="text-end">Recibida</th>
                        <th class="text-end">Entregada</th>
                        <th class="text-end">Restante</th>
                        <th width="80"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->fecha)->format('d/m/Y H:i') }}</td>
                            <td>{{ $row->producto }}</td>

                            <td class="text-end">
                                {{ number_format($row->cantidad_recibida,2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row->cantidad_entregada,2) }}
                            </td>

                            <td class="text-end">
                                <span class="badge bg-{{ $row->restante > 0 ? 'success' : 'secondary' }}">
                                    {{ number_format($row->restante,2) }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($row->restante > 0)
                                    <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEntrega"
                                            data-id="{{ $row->id_recepcion }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @endif

                                <a href="{{ route('seguridad.control-productos.historial',$row->id_recepcion) }}"
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No hay registros
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $registros->links() }}
            </div>
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
