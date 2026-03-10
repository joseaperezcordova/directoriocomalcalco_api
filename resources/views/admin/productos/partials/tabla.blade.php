<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
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
            <th class="text-end" onclick="ordenar('precio')">Precio {!! iconoOrden('precio',$sort,$dir) !!}</th>
            <th class="text-end" onclick="ordenar('stock')">Stock {!! iconoOrden('stock',$sort,$dir) !!}</th>
            <th></th>
            </tr>
            </thead>
            <tbody id="tablaProductos">
                @foreach($productos as $producto)
                <tr id="fila-{{ $producto->id }}">
                    <td>{{ $producto->id }}</td>
                    <td class="text-muted nombre">{{ $producto->nombre }}</td>
                    <td class="text-muted precio text-end">${{ number_format($producto->precio,2) }}</td>
                    <td class="text-muted stock text-end">{{ $producto->stock }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary btnEditar"
                                data-id="{{ $producto->id }}"
                                data-nombre="{{ $producto->nombre }}"
                                data-precio="{{ $producto->precio }}"
                                data-stock="{{ $producto->stock }}">
                            <i class="fas fa-edit"></i>
                        </button>
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
