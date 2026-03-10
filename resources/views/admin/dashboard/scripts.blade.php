@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    let graficaPuntos;
    let graficaCortesias;

    function cargarDashboard() {

        $.get("{{ route('admin.dashboard.data') }}", {
            fecha_inicio: $('#fecha_inicio').val(),
            fecha_fin: $('#fecha_fin').val()
        }, function (resp) {

            $('#ventas_total').text('$' + parseFloat(resp.ventas_total_general).toFixed(2));
            $('#tickets_total').text(resp.tickets_total_general);

            // Top productos
            let lista = '';
            resp.productos_top.forEach(p => {
                lista += `
                <li class="list-group-item d-flex justify-content-between">
                    ${p.nombre}
                    <span class="badge bg-primary">${p.total_vendidos}</span>
                </li>
            `;
            });
            $('#productos_top').html(lista);

            // Ventas por punto
            let labelsPuntos = resp.ventas_por_punto.map(p => p.nombre);
            let dataPuntos = resp.ventas_por_punto.map(p => p.total_ventas);

            if (graficaPuntos)
                graficaPuntos.destroy();

            graficaPuntos = new Chart(document.getElementById('graficaPuntos'), {
                type: 'bar',
                data: {
                    labels: labelsPuntos,
                    datasets: [{
                            label: 'Ventas $',
                            data: dataPuntos
                        }]
                }
            });

            // Cortesías por punto
            let labelsCortesias = resp.cortesias_por_punto.map(p => p.nombre);
            let dataCortesias = resp.cortesias_por_punto.map(p => p.total_cortesias);

            if (graficaCortesias)
                graficaCortesias.destroy();

            graficaCortesias = new Chart(document.getElementById('graficaCortesias'), {
                type: 'bar',
                data: {
                    labels: labelsCortesias,
                    datasets: [{
                            label: 'Cantidad Cortesías',
                            data: dataCortesias
                        }]
                }
            });

        });
    }

    $(document).ready(function () {

        let hoy = new Date();

        let fechaFin = new Date(hoy);
        let fechaInicio = new Date(hoy);

        fechaInicio.setDate(hoy.getDate() - 6); // últimos 7 días contando hoy

        function formato(fecha) {
            let mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
            let dia = fecha.getDate().toString().padStart(2, '0');
            return fecha.getFullYear() + '-' + mes + '-' + dia;
        }

        $('#fecha_inicio').val(formato(fechaInicio));
        $('#fecha_fin').val(formato(fechaFin));

        cargarDashboard();

        $('#btnFiltrar').click(function () {
            cargarDashboard();
        });

    });
</script>

@endpush