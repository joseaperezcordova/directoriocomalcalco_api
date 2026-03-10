<table class="table table-sm table-bordered">
    <thead class="table-light">
        <tr>
            <th>Producto</th>
            <th>Cant</th>
            <th>Precio</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->detalles as $d)
        <tr @if($d->precio_unitario == 0) class="table-warning" @endif>
            <td>
                {{ $d->producto->nombre }}
                @if($d->precio_unitario == 0)
                <span class="badge bg-warning text-dark">Cortesía</span>
                @endif
            </td>
            <td>{{ $d->cantidad }}</td>
            <td>
                ${{ number_format($d->precio_unitario,2) }}
            </td>
            <td>
                ${{ number_format($d->subtotal,2) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h5 class="text-end fw-bold">
    Total: ${{ number_format($venta->total, 2) }}
</h5>