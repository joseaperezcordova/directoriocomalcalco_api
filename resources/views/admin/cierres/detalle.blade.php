<h4 class="mb-3">Detalle del Cierre</h4>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Producto</th>
            <th>Inicial</th>
            <th>Entradas</th>
            <th>Vendido</th>
            <th>Cortesías</th>
            <th>Final Sistema</th>
            <th>Final Físico</th>
            <th>Diferencia</th>
        </tr>
    </thead>
    <tbody>
        @foreach($detalles as $d)
        <tr>
            <td>{{ $d->nombre }}</td>
            <td>{{ $d->inicial }}</td>
            <td>{{ $d->entradas }}</td>
            <td>{{ $d->vendido }}</td>
            <td>{{ $d->cortesias }}</td>
            <td>{{ $d->final_sistema }}</td>
            <td>{{ $d->final_fisico }}</td>
            <td>
                @if($d->diferencia != 0)
                    <span class="text-danger fw-bold">{{ $d->diferencia }}</span>
                @else
                    <span class="text-success">0</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>