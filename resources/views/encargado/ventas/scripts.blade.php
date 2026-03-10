@push('scripts')
<script>
    $(document).ready(function () {

        // ---- CARGAR DATOS ----
        $.ajax({
            url: "{{ route('encargado.ventas.data') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {

                // Limpiar encabezados y footer
                $('#headerVentas').empty();
                $('#footerVentas').empty();

                let columnas = [];

                // ------- ENCABEZADO -------
                $('#headerVentas').append('<th>ID</th>');
                $('#headerVentas').append('<th>Fecha</th>');
                $('#headerVentas').append('<th>Vendedor</th>');
                $('#headerVentas').append('<th>Forma de Pago</th>');
                $('#headerVentas').append('<th>Total</th>');
                $('#headerVentas').append('<th>Acciones</th>');

                columnas = [
                    {data: 'id', name: 'id'},
                    {data: 'fecha', name: 'fecha'},
                    {data: 'usuario', name: 'usuario'},
                    {data: 'pago', name: 'pago'},
                    {
                        data: 'total',
                        name: 'total',
                        render: function (d) {
                            return '$' + parseFloat(d).toFixed(2);
                        }
                    },
                    {
                        data: 'id',
                        render: function (id) {
                            return `
                            <button class="btn btn-sm btn-info btn-detalle" 
                                    data-id="${id}">
                                Ver
                            </button>
                        `;
                        }
                    },
                ];

                // ------- FOOTER (TOTAL DE VENTAS) -------
                $('#footerVentas').append('<th colspan="4" class="text-end">Total:</th>');
                $('#footerVentas').append('<th></th>');
                $('#footerVentas').append('<th></th>');

                // ------- INICIALIZAR DATATABLE -------
                $('#tablaVentas').DataTable({
                    order: [[0, 'desc']],
                    destroy: true,
                    data: response.ventas,
                    columns: columnas,
                    paging: true,
                    searching: true,
                    ordering: true,
                    pageLength: 25,
                    columnDefs: [
                        {orderable: false, targets: [5]}
                    ],
                    footerCallback: function (row, data, start, end, display) {
                        let api = this.api();

                        let total = api
                                .column(5, {page: 'current'})
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

        // ---- MODAL DETALLE ----
        $(document).on('click', '.btn-detalle', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('encargado.ventas.detalle') }}",
                type: "GET",
                data: {id},
                success: function (resp) {
                    $('#modalDetalleVentaBody').html(resp.html);
                    $('#modalDetalleVenta').modal('show');
                }
            });
        });
    }
    );
</script>
@endpush