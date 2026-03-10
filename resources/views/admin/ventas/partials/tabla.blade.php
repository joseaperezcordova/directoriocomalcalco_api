<div class="card">
    <div class="card-body table-responsive">

        <table class="table table-sm table-hover align-middle">
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

                    <th role="button" onclick="ordenar('id')">
                        # {!! iconoOrden('id', $sort, $dir) !!}
                    </th>

                    <th role="button" onclick="ordenar('folio')">
                        Folio {!! iconoOrden('folio', $sort, $dir) !!}
                    </th>

                    <th role="button" onclick="ordenar('user_id')">
                        Vendido por {!! iconoOrden('user_id', $sort, $dir) !!}
                    </th>

                    <th role="button" onclick="ordenar('created_at')">
                        Fecha {!! iconoOrden('created_at', $sort, $dir) !!}
                    </th>

                    <th role="button" onclick="ordenar('total')">
                        Total {!! iconoOrden('total', $sort, $dir) !!}
                    </th>

                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->folio }}</td>
                        <td>{{ $venta->vendedor->name ?? '—' }}</td>
                        <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                        <td>${{ number_format($venta->total, 2) }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary btnDetalle"
                                    data-id="{{ $venta->id }}">
                                <i class="fa fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Sin resultados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end px-2">
            {{ $ventas->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>
