<script>

    $(document).ready(function () {

        // Diferencia inventario
        $('.final_fisico').on('keyup change', function () {

            let sistema = parseFloat($(this).data('final'));
            let fisico = parseFloat($(this).val());

            if (isNaN(fisico))
                fisico = 0;

            let diferencia = fisico - sistema;

            let celda = $(this).closest('tr').find('.diferencia');

            celda.text(diferencia);

            if (diferencia != 0) {
                celda.removeClass('text-success')
                        .addClass('text-danger');
            } else {
                celda.removeClass('text-danger')
                        .addClass('text-success');
            }

        });

        // Diferencia efectivo
        $('#efectivo_contado').on('keyup change', function(){

        let sistema = {{ $totales['efectivo'] }};
        let contado = parseFloat($(this).val());

        if (isNaN(contado))
            contado = 0;

        let dif = contado - sistema;

        $('#dif_efectivo').val(dif);

        if (dif != 0) {
            $('#dif_efectivo').addClass('text-danger')
                    .removeClass('text-success');
        } else {
            $('#dif_efectivo').addClass('text-success')
                    .removeClass('text-danger');
        }

    });

    // Guardar corte
    $('#btnGuardar').click(function(){

    Swal.fire({
    title: '¿Confirmar corte?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar'
    }).then((result) => {

    if (result.isConfirmed){

    $.ajax({
    url: "{{ route('vendedor.corte.store') }}",
            method: "POST",
            data: $('#formCorte').serialize(),
            success: function(){

            Swal.fire({
            icon: 'success',
                    title: 'Corte guardado correctamente'
            }).then(() => {
            location.reload();
            });
            }
    });
    }

    });
    });
            }
    );
</script>