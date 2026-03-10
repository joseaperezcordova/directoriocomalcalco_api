@push('scripts')
<script>
    $(document).ready(function () {
        $.ajax({
            url: "{{ route('vendedor.inventario.data') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {

                let columnas = [];

                // --- Cabecera ---
                $('#headerInventario').append('<th>ID</th>');
                $('#headerInventario').append('<th>Producto</th>');
                columnas.push({data: 'id_producto', name: 'id_producto'});
                columnas.push({data: 'nombre', name: 'nombre'});

                response.puntos.forEach(function (punto) {
                    $('#headerInventario').append('<th>' + punto + '</th>');
                    columnas.push({data: punto, name: punto, className: 'text-end'});
                });

                // Columna de total
                $('#headerInventario').append('<th>Total</th>');
                columnas.push({
                    data: null,
                    name: 'total',
                    className: 'text-end',
                    render: function (data, type, row) {
                        let sum = 0;
                        response.puntos.forEach(function (punto) {
                            sum += parseFloat(row[punto]) || 0;
                        });
                        return '<strong>' + sum + '</strong>';
                    }
                });

                // --- Footer para totales ---
                $('#footerInventario').append('<th>Total</th>');
                response.puntos.forEach(function () {
                    $('#footerInventario').append('<th></th>');
                });
                $('#footerInventario').append('<th></th>'); // Footer total fila

                // --- Inicializar DataTable ---
                let table = $('#tablaInventario').DataTable({
                    data: response.data,
                    columns: columnas,
                    paging: true,
                    searching: true,
                    ordering: true,
                    pageLength: 25,
                    footerCallback: function (row, data, start, end, display) {
                        let api = this.api();

                        // Calcular total por columna (sólo puntos de venta)
                        api.columns().every(function (colIdx) {
                            // Saltar columna producto y columna total
                             if (colIdx === 0 || colIdx === api.columns().nodes().length - 1)
                                return;

                            let total = api
                                    .column(colIdx, {page: 'current'})
                                    .data()
                                    .reduce(function (a, b) {
                                        return parseFloat(a) + parseFloat(b);
                                    }, 0);

                            $(api.column(colIdx).footer()).html(total);
                        });
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
