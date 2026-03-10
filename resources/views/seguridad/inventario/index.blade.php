@extends('layouts.sbadmin')

@section('title', 'Inventario')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0">Inventario</h1>
    </div>
    <div class="card">
        <div class="card-body table-responsive">
            <table id="tablaInventario" 
                   class="table table-sm table-hover align-middle w-100">
                <thead>
                    <tr id="headerInventario">
                        <!-- Se llenará dinámicamente -->
                    </tr>
                </thead>
                <tfoot>
                    <tr id="footerInventario">
                        <!-- Totales por columna -->
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@include('seguridad.inventario.scripts')
@endsection
