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
            <th onclick="ordenar('name')">Usuario {!! iconoOrden('name',$sort,$dir) !!}</th>
            <th class="text-end" onclick="ordenar('email')">Email {!! iconoOrden('email',$sort,$dir) !!}</th>
            <th class="text-end" onclick="ordenar('rol')">Rol {!! iconoOrden('rol',$sort,$dir) !!}</th>
            <th></th>
            </tr>
            </thead>
            <tbody id="tablaUsuarios">
                @foreach($usuarios as $usuario)
                <tr id="fila-{{ $usuario->id }}">
                    <td>{{ $usuario->id }}</td>
                    <td class="text-muted name">{{ $usuario->name }}</td>
                    <td class="text-muted email text-end">{{ $usuario->email }}</td>
                    <td class="text-muted rol text-end">{{ $usuario->rol }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary btnEditar"
                                data-id="{{ $usuario->id }}"
                                data-name="{{ $usuario->name }}"
                                data-email="{{ $usuario->email }}"
                                data-rol="{{ $usuario->rol }}">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end p-2">
        {{ $usuarios->links('pagination::simple-bootstrap-5') }}
    </div>
</div>
