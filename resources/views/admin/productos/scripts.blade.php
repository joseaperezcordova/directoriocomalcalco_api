@push('scripts')
<script>
    let order = 'id';
    let dir = 'desc';
    function cargarTabla(page = 1) {
        $.get("{{ route('admin.productos.tabla') }}", {
            buscar: $('#buscar').val(),
            order: order,
            dir: dir,
            page: page
        }, function (html) {
            $('#contenedorTabla').html(html);
        });
    }

    function ordenar(columna) {
        dir = (dir === 'asc') ? 'desc' : 'asc';
        order = columna;
        cargarTabla();
    }

    $(document).ready(function () {

        // carga inicial
        cargarTabla();

        // búsqueda en tiempo real
        $('#buscar').on('keyup', function () {
            cargarTabla();
        });

        // paginación sin recargar
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            cargarTabla(page);
        });

        let modal = new bootstrap.Modal(document.getElementById('modalProducto'));

        // NUEVO
        $('#btnNuevoProducto').click(function () {
            $('#formProducto')[0].reset();
            $('#producto_id').val('');
            $('#tituloModal').text('Nuevo producto');
            modal.show();
        });

        // EDITAR
        $('.btnEditar').click(function () {
            $('#producto_id').val($(this).data('id'));
            $('#nombre').val($(this).data('nombre'));
            $('#precio').val($(this).data('precio'));
            $('#stock').val($(this).data('stock'));
            $('#tituloModal').text('Editar producto');
            modal.show();
        });

        // GUARDAR (CREATE / UPDATE)
        $('#formProducto').submit(function (e) {
            e.preventDefault();

            let id = $('#producto_id').val();
            let url = id
                    ? `/admin/productos/${id}`
                    : `/admin/productos`;

            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: {
                    _token: '{{ csrf_token() }}',
                    nombre: $('#nombre').val(),
                    precio: $('#precio').val(),
                    stock: $('#stock').val()
                },
                success: function (res) {
                    let p = res.producto;

                    if (id) {
                        // 🟡 ACTUALIZAR FILA
                        let fila = $('#fila-' + id);
                        fila.find('.nombre').text(p.nombre);
                        fila.find('.precio').text(p.precio);
                        fila.find('.stock').text(p.stock);

                        // actualizar data del botón editar
                        fila.find('.btnEditar')
                                .data('nombre', p.nombre)
                                .data('precio', p.precio)
                                .data('stock', p.stock);

                    } else {
                        // 🟢 AGREGAR NUEVA FILA
                        let nuevaFila = `
                <tr id="fila-${p.id}">
                    <td>${p.id}</td>
                    <td class="nombre">${p.nombre}</td>
                    <td class="precio">${p.precio}</td>
                    <td class="stock">${p.stock}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btnEditar"
                            data-id="${p.id}"
                            data-nombre="${p.nombre}"
                            data-precio="${p.precio}"
                            data-stock="${p.stock}">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>`;
                        cargarTabla();
                    }
                    // 1️⃣ Quitar foco (evita el warning de aria-hidden)
                    document.activeElement.blur();

                    // 2️⃣ Cerrar modal
                    const modalEl = document.getElementById('modalProducto');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    // 3️⃣ Limpiar formulario
                    $('#formProducto')[0].reset();

                    // 4️⃣ Mostrar mensaje de confirmación (toast)
                    $('#toastMensaje').text(res.message);

                    const toastEl = document.getElementById('toastSuccess');
                    const toast = new bootstrap.Toast(toastEl, {delay: 3000});
                    toast.show();

                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errores = xhr.responseJSON.errors;
                        alert(Object.values(errores)[0][0]);
                    }
                }
            });
        });

    });
    $(document).on('click', '.btnEditar', function () {
        $('#producto_id').val($(this).data('id'));
        $('#nombre').val($(this).data('nombre'));
        $('#precio').val($(this).data('precio'));
        $('#stock').val($(this).data('stock'));

        $('#tituloModal').text('Editar producto');
        $('#modalProducto').modal('show');
    });

</script>
@endpush

