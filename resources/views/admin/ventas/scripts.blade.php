@push('scripts')
<script>
    $(document).ready(function () {

        $.ajax({
            url: "{{ route('admin.ventas.data') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {

                $('#headerVentas').empty();
                $('#footerVentas').empty();

                $('#headerVentas').append('<th>ID</th>');
                $('#headerVentas').append('<th>Fecha</th>');
                $('#headerVentas').append('<th>Vendedor</th>');
                $('#headerVentas').append('<th>Forma de Pago</th>');
                $('#headerVentas').append('<th>Total</th>');
                $('#headerVentas').append('<th>Cortesía</th>');
                $('#headerVentas').append('<th>Acciones</th>');

                $('#footerVentas').append('<th colspan="4" class="text-end">Total página:</th>');
                $('#footerVentas').append('<th></th>');
                $('#footerVentas').append('<th></th>');
                $('#footerVentas').append('<th></th>');

                $('#tablaVentas').DataTable({
                    destroy: true,
                    order: [[0, 'desc']],
                    data: response.ventas ?? [],
                            columns: [
                                {data: 'id'},
                                {data: 'fecha'},
                                {data: 'usuario'},
                                {data: 'pago'},
                                {
                                    data: 'total',
                                    render: function (d) {
                                        return '$' + parseFloat(d).toFixed(2);
                                    }
                                },
                                {
                                    data: 'tiene_cortesia',
                                    render: function (d) {
                                        return d
                                                ? '<span class="badge bg-warning text-dark">Sí</span>'
                                                : '<span class="badge bg-secondary">No</span>';
                                    }
                                },
                                {
                                    data: 'id',
                                    render: function (id) {
                                        return `
                                <button class="btn btn-sm btn-info btn-detalle" data-id="${id}">
                                    Ver
                                </button>
                            `;
                                    }
                                }
                            ],
                    columnDefs: [
                        {orderable: false, targets: [5, 6]}
                    ],
                    footerCallback: function (row, data, start, end, display) {

                        let api = this.api();

                        let total = api
                                .column(4, {page: 'current'})
                                .data()
                                .reduce(function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);

                        $(api.column(4).footer()).html('$' + total.toFixed(2));
                    },
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                    }
                });
            }
        });

        // ---- DETALLE ----
        $(document).on('click', '.btn-detalle', function () {

            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.ventas.detalle') }}",
                type: "GET",
                data: {id},
                success: function (resp) {
                    $('#modalDetalleVentaBody').html(resp.html);
                    $('#modalDetalleVenta').modal('show');
                }
            });
        });

    });
</script>
@endpush