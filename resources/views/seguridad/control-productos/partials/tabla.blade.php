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
                        Entregado por {!! iconoOrden('user_id', $sort, $dir) !!}
                    </th>

                    <th role="button" onclick="ordenar('created_at')">
                        Fecha {!! iconoOrden('created_at', $sort, $dir) !!}
                    </th>

                    <th class="text-center">
                        Productos
                    </th>

                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($entregas as $entrega)
                    <tr>
                        <td>{{ $entrega->id }}</td>
                        <td>{{ $entrega->folio }}</td>
                        <td>{{ $entrega->usuario->name ?? '—' }}</td>
                        <td>{{ $entrega->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            {{ $entrega->detalles->count() }}
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary btnDetalle"
                                    data-id="{{ $entrega->id }}">
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
            {{ $entregas->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>
