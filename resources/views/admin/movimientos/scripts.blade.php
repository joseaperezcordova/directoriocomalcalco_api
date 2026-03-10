@push('scripts')
<script>
    $(document).ready(function () {
        $.ajax({
            url: "{{ route('admin.movimientos.data') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {
                // Limpiar header y footer por si se recarga
                $('#headerMovimientos').empty();
                $('#footerMovimientos').empty();

                let columnas = [];

                // ----- HEADER -----
                $('#headerMovimientos').append('<th>ID</th>');
                $('#headerMovimientos').append('<th>Tipo</th>');
                $('#headerMovimientos').append('<th>Producto</th>');
                $('#headerMovimientos').append('<th>Cantidad</th>');
                $('#headerMovimientos').append('<th>Origen</th>');
                $('#headerMovimientos').append('<th>Destino</th>');
                $('#headerMovimientos').append('<th>Usuario</th>');
                $('#headerMovimientos').append('<th>Fecha</th>');

                columnas = [
                    {data: 'id', name: 'id'},
                    {data: 'tipo', name: 'tipo'},
                    {data: 'producto', name: 'producto'},
                    {data: 'cantidad', name: 'cantidad'},
                    {data: 'punto_origen', name: 'punto_origen'},
                    {data: 'punto_destino', name: 'punto_destino'},
                    {data: 'usuario', name: 'usuario'},
                    {data: 'fecha', name: 'fecha'}
                ];

                // ----- FOOTER (solo total cantidad) -----
                $('#footerMovimientos').append('<th colspan="3" style="text-align:right">Total:</th>');
                $('#footerMovimientos').append('<th></th>'); // cantidad
                $('#footerMovimientos').append('<th colspan="4"></th>');

                // ----- INICIALIZAR DATATABLE -----
                $('#tablaMovimientos').DataTable({
                    order: [[0, 'desc']],
                    destroy: true,
                    data: response.data,
                    columns: columnas,
                    paging: true,
                    searching: true,
                    ordering: true,
                    pageLength: 25,
                    footerCallback: function (row, data, start, end, display) {
                        let api = this.api();

                        let total = api
                                .column(3, {page: 'current'}) // columna cantidad
                                .data()
                                .reduce(function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);

                        $(api.column(3).footer()).html(total);
                    },
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                    }
                });

            }
        });

    });
</script>
@endpush
