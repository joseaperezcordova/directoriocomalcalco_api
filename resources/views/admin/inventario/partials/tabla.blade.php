<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    @php
                    function iconoOrden($campo, $sort, $dir) {
                    if ($sort !== $campo) return '';
                    return $dir === 'asc'
                    ? '<i class="fas fa-sort-up ms-1"></i>'
            : '<i class="fas fa-sort-down ms-1"></i>';
            }
            @endphp

            <th onclick="ordenar('id')"># {!! iconoOrden('id',$sort,$dir) !!}</th>
            <th onclick="ordenar('nombre')">Producto {!! iconoOrden('nombre',$sort,$dir) !!}</th>
            <th>Precio</th>

            @foreach($puntosVenta as $pv)
            <th>{{ $pv->nombre }}</th>
            @endforeach

            <th>Total</th>
            </tr>
            </thead>

            <tbody>
                @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>

                    @php $total = 0; @endphp

                    @foreach($puntosVenta as $pv)
                    @php
                    $stock = $producto->stocks
                    ->firstWhere('punto_venta_id', $pv->id);

                    $actual = $stock->cantidad_actual ?? 0;
                    $minimo = $producto->minimo; // 👈 AQUÍ

                    if ($actual <= $minimo) {
                    $clase = 'danger';
                    } elseif ($actual <= ($minimo + 3)) {
                    $clase = 'warning';
                    } else {
                    $clase = 'success';
                    }
                    @endphp

                    <td>
                        <span class="badge bg-{{ $clase }}">
                            {{ $actual }}
                        </span>
                        <br>
                        <small class="text-muted">
                            mín {{ $minimo }}
                        </small>
                    </td>
                    @endforeach
                    <td>
                        <strong>{{ $total }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end p-2">
        {{ $productos->links('pagination::simple-bootstrap-5') }}
    </div>
</div>