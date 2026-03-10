@push('scripts')
<script>
    let order = 'id';
    let dir = 'desc';

    function cargarTabla(page = 1) {
        $.get("{{ route('seguridad.entrega.tabla') }}", {
            desde: $('#desde').val() || '',
            hasta: $('#hasta').val() || '',
            order: order,
            dir: dir,
            page: page
        }, function (html) {
            $('#contenedorTabla').html(html);
        });
    }

    function ordenar(columna) {
        if (order === columna) {
            dir = (dir === 'asc') ? 'desc' : 'asc';
        } else {
            order = columna;
            dir = 'asc';
        }
        cargarTabla();
    }

    $(document).ready(function () {

        // 🔹 carga inicial
        cargarTabla();

        // 🔹 filtros por fecha
        $('#desde, #hasta').on('change', function () {
            cargarTabla();
        });

        // 🔹 botón filtrar
        $('#btnFiltrar').on('click', function () {
            cargarTabla();
        });

        // 🔹 limpiar filtros
        $('#btnLimpiar').on('click', function () {
            $('#desde').val('');
            $('#hasta').val('');
            cargarTabla();
            history.replaceState(null, '', window.location.pathname);
        });

        // 🔹 paginación AJAX
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            cargarTabla(page);
        });

        // 🔹 nueva entrega
        $('#btnNuevaEntrega').on('click', function () {
            new bootstrap.Modal(
                document.getElementById('modalCrearEntrega')
            ).show();
        });

        // 🔹 detalle de entrega
        $(document).on('click', '.btnDetalle', function () {
            const id = $(this).data('id');
            const url = "{{ url('seguridad/entrega') }}/" + id + "/detalle";

            fetch(url)
                .then(r => r.json())
                .then(e => {

                    $('#mFolio').text(e.folio);
                    $('#mFecha').text(e.fecha);
                    $('#mUsuario').text(e.usuario);

                    let tbody = $('#mDetalle');
                    tbody.empty();

                    e.productos.forEach(p => {
                        tbody.append(`
                            <tr>
                                <td>${p.producto}</td>
                                <td class="text-center">${p.cantidad}</td>
                            </tr>
                        `);
                    });

                    new bootstrap.Modal(
                        document.getElementById('modalDetalleEntrega')
                    ).show();
                });
        });

    });
</script>
@endpush
