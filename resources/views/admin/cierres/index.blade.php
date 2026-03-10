@extends('layouts.sbadmin')
@section('title','Ventas Realizadas')
@section('content')
<div class="container-fluid px-4">

    <h1 class="mt-4">Listado de Cierres</h1>

    <div class="card shadow mb-4 border-0">
        <div class="card-body table-responsive">

            <table class="table table-bordered" id="tablaCierres">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Punto</th>
                        <th>Usuario</th>
                        <th>Efectivo Sistema</th>
                        <th>Efectivo Contado</th>
                        <th>Diferencia</th>
                        <th>Tarjeta</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body" id="contenidoDetalle"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@include('admin.cierres.scripts')
@endpush