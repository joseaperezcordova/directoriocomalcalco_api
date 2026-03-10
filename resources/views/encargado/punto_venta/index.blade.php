@extends('layouts.sbadmin')

@section('title', 'Punto de Venta')

@section('content')
<div class="container-fluid px-4">

    <h1 class="mt-4">Punto de Venta</h1>

    <div class="row mt-4">

        {{-- PRODUCTOS --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-box me-1"></i>
                    Productos
                </div>

                <div class="card-body">
                    <div class="row g-3">


                    </div>
                </div>
            </div>
        </div>

        {{-- CARRITO --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">

                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Venta Actual
                </div>

                <div class="card-body">

                    {{-- Tabla carrito --}}
                    <div class="table-responsive mb-3">
                        <table class="table table-sm" id="tabla-carrito">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th width="60">Cant</th>
                                    <th width="80">$</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    {{-- Total --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Total:</strong>
                        <h5 class="mb-0 text-primary">
                            $<span id="total">0.00</span>
                        </h5>
                    </div>

                    {{-- Forma de pago estilo segmentado --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Forma de Pago</label>

                        <div class="segmented-control">
                            <input type="radio" name="forma_pago" id="efectivo"
                                   value="efectivo" checked>
                            <label for="efectivo">Efectivo</label>

                            <input type="radio" name="forma_pago" id="tarjeta"
                                   value="tarjeta">
                            <label for="tarjeta">Tarjeta</label>

                            <span class="slider"></span>
                        </div>
                    </div>

                    {{-- Botón cobrar --}}
                    <button class="btn btn-success w-100" id="btn-pagar">
                        Cobrar
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .producto-card {
        cursor: pointer;
        transition: all .2s ease;
    }
    .producto-card:hover {
        transform: scale(1.03);
    }
    .btn-forma-pago.active {
        background-color: var(--bs-success);
        color: white;
    }
</style>

@endsection

@push('scripts')
@include('encargado.punto_venta.scripts')
@endpush

<style>
.segmented-control {
    position: relative;
    display: flex;
    border: 2px solid #0d6efd;
    border-radius: 50px;
    overflow: hidden;
    height: 45px;
}

.segmented-control input {
    display: none;
}

.segmented-control label {
    flex: 1;
    text-align: center;
    line-height: 45px;
    cursor: pointer;
    font-weight: 600;
    z-index: 2;
    margin: 0;
    color: #0d6efd;
    transition: 0.3s;
}

.segmented-control .slider {
    position: absolute;
    width: 50%;
    height: 100%;
    background: #0d6efd;
    top: 0;
    left: 0;
    border-radius: 50px;
    transition: 0.3s;
    z-index: 1;
}

#tarjeta:checked ~ .slider {
    left: 50%;
}

.segmented-control input:checked + label {
    color: #fff;
}
</style>