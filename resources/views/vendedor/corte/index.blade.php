@extends('layouts.sbadmin')
@section('title', 'Punto Venta')
@section('content')
<div class="container-fluid px-4">

    <h1 class="mt-4">Corte Diario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">
            Punto de Venta: {{ $puntoVenta->nombre }}
        </li>
    </ol>

    <form id="formCorte">
        @csrf

        <input type="hidden" name="id_punto_venta" value="{{ $puntoVenta->id }}">
        <input type="hidden" name="total_efectivo_sistema" value="{{ $totales['efectivo'] }}">
        <input type="hidden" name="total_tarjeta_sistema" value="{{ $totales['tarjeta'] }}">
        <input type="hidden" name="total_general_sistema" value="{{ $totales['total'] }}">
        
        <!-- INVENTARIO CARD -->
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-boxes me-2"></i>
                Resumen de Inventario del Día
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Inicial</th>
                                <th class="text-center">Entradas</th>
                                <th class="text-center text-danger">Vendido</th>
                                <th class="text-center text-warning">Cortesías</th>
                                <th class="text-center">Final Sistema</th>
                                <th class="text-center">Final Físico</th>
                                <th class="text-center">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $p)

                            <tr>
                                <td>{{ $p->nombre }}</td>
                                <td class="text-center">{{ $p->inicial }}</td>
                                <td class="text-center text-success">{{ $p->entradas }}</td>
                                <td class="text-center text-danger">{{ $p->vendido }}</td>
                                <td class="text-center text-warning fw-bold">{{ $p->cortesias }}</td>
                                <td class="text-center fw-bold">{{ $p->final_sistema }}</td>

                                <td style="width:120px;">
                                    <input type="number"
                                           class="form-control form-control-sm final_fisico"
                                           name="productos[{{ $p->id }}][final_fisico]"
                                           data-final="{{ $p->final_sistema }}"
                                           value="{{ $p->final_sistema }}">
                                </td>

                                <td class="text-center diferencia fw-bold">0</td>
                        <input type="hidden" name="productos[{{ $p->id }}][inicial]" value="{{ $p->inicial }}">
                        <input type="hidden" name="productos[{{ $p->id }}][entradas]" value="{{ $p->entradas }}">
                        <input type="hidden" name="productos[{{ $p->id }}][vendido]" value="{{ $p->vendido }}">
                        <input type="hidden" name="productos[{{ $p->id }}][cortesias]" value="{{ $p->cortesias }}">
                        <input type="hidden" name="productos[{{ $p->id }}][final_sistema]" value="{{ $p->final_sistema }}">
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- VENTAS CARD -->
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-success text-white">
                <i class="fas fa-cash-register me-2"></i>
                Resumen de Ventas
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Efectivo (Sistema)</label>
                        <input type="text" class="form-control"
                               value="$ {{ number_format($totales['efectivo'],2) }}"
                               readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tarjeta (Sistema)</label>
                        <input type="text" class="form-control"
                               value="$ {{ number_format($totales['tarjeta'],2) }}"
                               readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Total General</label>
                        <input type="text" class="form-control fw-bold"
                               value="$ {{ number_format($totales['total'],2) }}"
                               readonly>
                    </div>

                </div>

                <hr>

                <div class="row g-3 mt-2">

                    <div class="col-md-3">
                        <label class="form-label">Efectivo Contado</label>
                        <input type="number" step="0.01"
                               name="efectivo_contado"
                               id="efectivo_contado"
                               class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Diferencia Efectivo</label>
                        <input type="text"
                               id="dif_efectivo"
                               class="form-control fw-bold"
                               readonly>
                    </div>

                </div>

            </div>
        </div>

        <!-- BOTÓN -->
        <div class="text-end mb-5">
            <button type="button" id="btnGuardar"
                    class="btn btn-primary btn-lg shadow">
                <i class="fas fa-save me-1"></i>
                Guardar Corte
            </button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
@include('vendedor.corte.scripts')
@endpush