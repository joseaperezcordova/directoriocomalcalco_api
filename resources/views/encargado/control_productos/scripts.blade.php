@push('scripts')
<script>
    $(document).ready(function () {
        $('#tabla').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            order: [[0, 'desc']],
            ajax: "{{ route('encargado.control-productos.data') }}",
            columns: [
                {data: 'id'},
                {data: 'fecha'},
                {data: 'producto'},
                {data: 'cantidad_recibida'},
                {data: 'cantidad_entregada'},
                {data: 'restante'},
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    createdCell: function (td, cellData, rowData) {

                        if (rowData.restante <= 0) {
                            $(td).html("<i class='fa-solid fa-check'></i>");
                            return;
                        }

                        var b = $("<button class='btn btn btn-outline-info btn-sm'><i class='fas fa-dolly'></i></button>")
                                .click(function () {

                                    $('#recepcion_id').val(cellData);
                                    $('#recepcion_id_visible').val(cellData);

                                    $('#producto_nombre').val(rowData.producto);
                                    $('#cantidad_disponible').val(rowData.restante);

                                    $('#cantidad_entregar')
                                            .val('')
                                            .attr('max', rowData.restante)
                                            .removeClass('is-invalid');

                                    $('#modalEntregar').modal('show');
                                });

                        $(td).html(b);
                    }
                }
            ]
        });
        $('#btnNuevaEntrega').on('click', function () {
            new bootstrap.Modal(
                    document.getElementById('modalRecepcion')
                    ).show();
        });
        $('#modalRecepcion').on('show.bs.modal', () => {
            items = [];
            $('#lista tbody').html('');
            $('#total').text(0);

            $.get("{{ route('encargado.control-productos.productos') }}", data => {
                let o = '<option>Seleccione</option>';
                data.forEach(p => o += `<option value="${p.id}">${p.nombre}</option>`);
                $('#producto').html(o);
            });
        });

        let items = [];

        $('#agregar').click(() => {

            let id = $('#producto').val();
            let text = $('#producto option:selected').text();
            let cant = parseInt($('#cantidad').val());

            if (!id || cant <= 0 || isNaN(cant)) {
                alert('Seleccione un producto y una cantidad válida');
                return;
            }

            // 🔎 Buscar si ya existe
            let existente = items.find(item => item.producto_id == id);

            if (existente) {
                // ✅ Si existe, sumar cantidad
                existente.cantidad += cant;

                // Actualizar cantidad en tabla
                $(`#lista tbody tr[data-id="${id}"] .cantidad`)
                        .text(existente.cantidad);

            } else {
                // 🆕 Si no existe, agregar nuevo
                items.push({producto_id: id, cantidad: cant});

                $('#lista tbody').append(`
            <tr data-id="${id}">
                <td>${text}</td>
                <td class="cantidad">${cant}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger quitar">
                        Quitar
                    </button>
                </td>
            </tr>
        `);
            }

            actualizarTotal();

            $('#producto').val('');
            $('#cantidad').val('');
        });


// ➖ Quitar 1 por click
        $(document).on('click', '.quitar', function () {

            let row = $(this).closest('tr');
            let id = row.data('id');

            let item = items.find(item => item.producto_id == id);

            if (item) {
                item.cantidad -= 1;

                if (item.cantidad <= 0) {
                    // 🗑 Eliminar completamente
                    items = items.filter(i => i.producto_id != id);
                    row.remove();
                } else {
                    // 🔄 Actualizar cantidad visual
                    row.find('.cantidad').text(item.cantidad);
                }
            }

            actualizarTotal();
        });


// 🔢 Actualizar total general
        function actualizarTotal() {
            let total = items.reduce((a, b) => a + b.cantidad, 0);
            $('#total').text(total);
        }

        $('#guardar').click(() => {
            const $btn = $('#guardar');
            if ($btn.prop('disabled'))
                return; // si ya está deshabilitado, salir
            $btn.prop('disabled', true);
            $.post("{{ route('encargado.control-productos.store') }}",
                    {_token: '{{ csrf_token() }}', items},
                    () => {
                $('#modalRecepcion').modal('hide');
                $('#tabla').DataTable().ajax.reload(null, false);
                // Limpiar arreglo
                items = [];
                $('#lista tbody').empty();
                $('#total').text(0);
                const toastEl = document.getElementById('toastSuccess');
                const toast = new bootstrap.Toast(toastEl, {delay: 3000});
                toast.show();
            }
            ).always(() => {
                // Volver a habilitar el botón cuando termine la petición
                $btn.prop('disabled', false);
            });
        });
        $('#cantidad_entregar').on('input', function () {
            let maximo = parseInt($(this).attr('max'));
            let valor = parseInt($(this).val());
            if (valor > maximo) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        $('#confirmarEntrega').click(function () {

            let id = $('#recepcion_id').val();
            let cantidad = parseInt($('#cantidad_entregar').val());
            let maximo = parseInt($('#cantidad_entregar').attr('max'));
            let id_punto_venta = parseInt($('#id_punto_venta').val());

            if (!cantidad || cantidad <= 0) {
                alert('Cantidad inválida');
                return;
            }

            if (cantidad > maximo) {
                alert('No puedes entregar más de lo disponible');
                return;
            }
            const $btn = $('#confirmarEntrega');
            if ($btn.prop('disabled'))
                return; // si ya está deshabilitado, salir
            $btn.prop('disabled', true);
            $.post("{{ route('encargado.control-productos.entrega') }}", {
                _token: '{{ csrf_token() }}',
                recepcion_id: id,
                cantidad: cantidad,
                id_punto_venta: id_punto_venta
            }, function () {

                $('#modalEntregar').modal('hide');
                $('#tabla').DataTable().ajax.reload(null, false);
                const toastEl = document.getElementById('toastSuccess');
                const toast = new bootstrap.Toast(toastEl, {delay: 3000});
                toast.show();
            }).always(() => {
                // Volver a habilitar el botón cuando termine la petición
                $btn.prop('disabled', false);
            });
        });
    });
</script>
@endpush
