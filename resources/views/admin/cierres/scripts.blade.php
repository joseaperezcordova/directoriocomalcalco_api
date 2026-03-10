@push('scripts')
<script>
    $(document).ready(function () {

        let tabla = $('#tablaCierres').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            ajax: "{{ route('admin.cierres.data') }}",
            columns: [
                {data: 'fecha'},
                {data: 'punto'},
                {data: 'usuario'},
                {data: 'total_efectivo_sistema'},
                {data: 'efectivo_contado'},
                {
                    data: null,
                    render: function (data) {
                        let dif = parseFloat(data.efectivo_contado) - parseFloat(data.total_efectivo_sistema);
                        return dif.toFixed(2);
                    }
                },
                {data: 'total_tarjeta_sistema'},
                {data: 'total_general_sistema'},
                {
                    data: null,
                    render: function (data) {
                        let dif = parseFloat(data.efectivo_contado) - parseFloat(data.total_efectivo_sistema);
                        if (dif == 0) {
                            return '<span class="badge bg-success">Correcto</span>';
                        } else {
                            return '<span class="badge bg-danger">Con Diferencia</span>';
                        }
                    }
                },
                {
                    data: 'id',
                    render: function (id) {
                        return `<button class="btn btn-primary btn-sm btn-detalle" data-id="${id}">
                                Ver
                            </button>`;
                    }
                }
            ]
        });

        $(document).on('click', '.btn-detalle', function () {

            let id = $(this).data('id');

            $.get("{{ url('admin/cierres/detalle') }}/" + id, function (resp) {
                $('#contenidoDetalle').html(resp.html);
                $('#modalDetalle').modal('show');
            });

        });

    });
</script>
@endpush
