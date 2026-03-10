@extends('layouts.sbadmin')

@section('title', 'Ventas Realizadas')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0">Ventas</h1>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table id="tablaVentas" 
                   class="table table-sm table-hover align-middle w-100">
                <thead>
                    <tr id="headerVentas">
                        <!-- Se llenará dinámicamente -->
                    </tr>
                </thead>
                <tfoot>
                    <tr id="footerVentas">
                        <!-- Totales -->
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
<div class="modal fade" id="modalDetalleVenta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle de Venta</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalDetalleVentaBody">
                <!-- Aquí se carga la vista vendedor.ventas.detalle -->
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('vendedor.ventas.scripts')
@endsection