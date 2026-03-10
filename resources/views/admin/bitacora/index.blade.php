@extends('layouts.sbadmin')

@section('title', 'Bitacora')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h4 mb-0">Bitacora</h1>
    </div>
    <div class="card">
        <div class="card-body table-responsive">
            <table id="tablaBitacora" 
                   class="table table-sm table-hover align-middle w-100">
                <thead>
                    <tr id="headerBitacora">
                        <!-- Se llenará dinámicamente -->
                    </tr>
                </thead>
                <tfoot>
                    <tr id="footerBitacora">
                        <!-- Totales por columna -->
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@include('admin.bitacora.scripts')
@endsection
