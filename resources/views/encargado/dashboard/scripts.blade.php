@push('scripts')
<script>
    $(document).ready(function () {
        let tabla = $('#tablaMovimientos').DataTable({
            order: [[0, 'desc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            ajax: {
                url: "{{ route('encargado.dashboard.data') }}",
                dataSrc: function (json) {
                    // Actualizar cards del dashboard
                    $('#productosRecibidosHoy').text(json.totales.recibidos_hoy);
                    $('#productosEntregadosHoy').text(json.totales.entregados_hoy);
                    $('#pendientesEntrega').text(json.totales.pendientes);

                    return json.data;
                }
            },
            columns: [
                {data: 'id'},
                {data: 'tipo'},
                {data: 'producto'},
                {data: 'cantidad'},
                {data: 'punto_origen'},
                {data: 'punto_destino'},
                {data: 'usuario'},
                {data: 'fecha'}
            ]
        });

    });
</script>
@endpush
