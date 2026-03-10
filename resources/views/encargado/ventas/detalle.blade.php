<table class="table table-sm">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cant</th>
            <th>Precio</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->detalles as $d)
        <tr>
            <td>{{ $d->producto->nombre }}</td>
            <td>{{ $d->cantidad }}</td>
            <td>${{ number_format($d->precio_unitario,2) }}</td>
            <td>${{ number_format($d->subtotal,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h5 class="text-end fw-bold">Total: ${{ number_format($venta->total, 2) }}</h5>