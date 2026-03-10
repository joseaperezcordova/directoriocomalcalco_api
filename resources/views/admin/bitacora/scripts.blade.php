@push('scripts')
<script>
    $(document).ready(function () {

        $.ajax({
            url: "{{ route('admin.bitacora.data') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {

                $('#headerBitacora').empty();
                $('#footerBitacora').empty();

                // ----- HEADER -----
                $('#headerBitacora').append('<th>ID</th>');
                $('#headerBitacora').append('<th>Acción</th>');
                $('#headerBitacora').append('<th>Producto</th>');
                $('#headerBitacora').append('<th>Cantidad</th>');
                $('#headerBitacora').append('<th>Antes</th>');
                $('#headerBitacora').append('<th>Después</th>');
                $('#headerBitacora').append('<th>Origen</th>');
                $('#headerBitacora').append('<th>Destino</th>');
                $('#headerBitacora').append('<th>Usuario</th>');
                $('#headerBitacora').append('<th>Fecha</th>');

                let columnas = [
                    {data: 'id'},
                    {data: 'accion'},
                    {data: 'producto'},
                    {data: 'cantidad'},
                    {data: 'antes'},
                    {data: 'despues'},
                    {data: 'punto_origen'},
                    {data: 'punto_destino'},
                    {data: 'usuario'},
                    {data: 'fecha'}
                ];

                // ----- FOOTER -----
                $('#footerBitacora').append('<th colspan="3" style="text-align:right">Total cantidad:</th>');
                $('#footerBitacora').append('<th></th>');
                $('#footerBitacora').append('<th colspan="6"></th>');

                $('#tablaBitacora').DataTable({
                    destroy: true,
                    order: [[0, 'desc']],
                    data: response.data,
                    columns: columnas,
                    pageLength: 25,
                    paging: true,
                    searching: true,
                    ordering: true,
                    footerCallback: function (row, data, start, end, display) {

                        let api = this.api();

                        let total = api
                                .column(3, {page: 'current'})
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
