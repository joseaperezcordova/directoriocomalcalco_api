@push('scripts')
<script>
    let order = 'id';
    let dir = 'desc';
    let modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    function cargarTabla(page = 1) {
        $.get("{{ route('admin.usuarios.tabla') }}", {
            buscar: $('#buscar').val(),
            order: order,
            dir: dir,
            page: page
        }, function (html) {
            $('#contenedorTabla').html(html);
        });
    }

    function ordenar(columna) {
        dir = (dir === 'asc') ? 'desc' : 'asc';
        order = columna;
        cargarTabla();
    }

    $(document).ready(function () {
        // carga inicial
        cargarTabla();

        // búsqueda en tiempo real
        $('#buscar').on('keyup', function () {
            cargarTabla();
        });

        // paginación sin recargar
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            cargarTabla(page);
        });

        $('#btnNuevoUsuario').click(function () {
            $('#errorBackend').addClass('d-none').text('');
            $('#formUsuario')[0].reset();
            $('#usuario_id').val('');

            $('#chkCambiarPasswordContainer').hide();
            $('#contenedorPassword').removeClass('d-none');
            $('#password, #password_confirmation').prop('required', true);

            $('#errorPassword').addClass('d-none');
            $('#errorBackend').addClass('d-none').text('');

            $('#tituloModal').text('Nuevo usuario');
            modal.show();
        });

        // GUARDAR (CREATE / UPDATE)
        $('#formUsuario').submit(function (e) {
            e.preventDefault();

            let id = $('#usuario_id').val();
            let cambiarPassword = $('#chkCambiarPassword').is(':checked');
            // 🔐 VALIDAR CONTRASEÑA SOLO EN CREAR
            if (!id || cambiarPassword) {
                let password = $('#password').val();
                let confirm = $('#password_confirmation').val();

                if (password !== confirm) {
                    $('#errorPassword').removeClass('d-none');
                    return;
                }
            }

            $('#errorPassword').addClass('d-none');

            let url = id
                    ? `/admin/usuarios/${id}`
                    : `/admin/usuarios`;

            let method = id ? 'PUT' : 'POST';

            // 📦 DATA BASE
            let data = {
                _token: '{{ csrf_token() }}',
                name: $('#name').val(),
                email: $('#email').val(),
                rol: $('#rol').val()
            };

            // ➕ AGREGAR PASSWORD SOLO SI ES NUEVO
            if (!id || cambiarPassword) {
                data.password = $('#password').val();
                data.password_confirmation = $('#password_confirmation').val();
            }

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function (res) {
                    let p = res.usuario;

                    if (id) {
                        // 🟡 ACTUALIZAR FILA
                        let fila = $('#fila-' + id);
                        fila.find('.name').text(p.name);
                        fila.find('.email').text(p.email);
                        fila.find('.rol').text(p.rol);

                        fila.find('.btnEditar')
                                .data('name', p.name)
                                .data('email', p.email)
                                .data('rol', p.rol);

                    } else {
                        // 🟢 NUEVO USUARIO → RECARGAR TABLA
                        cargarTabla();
                    }

                    // 1️⃣ Quitar foco (evita warning aria-hidden)
                    document.activeElement.blur();

                    // 2️⃣ Cerrar modal
                    const modalEl = document.getElementById('modalUsuario');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    // 3️⃣ Limpiar formulario
                    $('#formUsuario')[0].reset();

                    // 4️⃣ Toast de éxito
                    $('#toastMensaje').text(res.message);
                    const toastEl = document.getElementById('toastSuccess');
                    const toast = new bootstrap.Toast(toastEl, {delay: 3000});
                    toast.show();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errores = xhr.responseJSON.errors;
                        let primerError = Object.values(errores)[0][0];

                        $('#errorBackend')
                                .text(primerError)
                                .removeClass('d-none');
                    }
                }
            });
        });


    });
    $('#contenedorTabla').on('click', '.btnEditar', function () {
        $('#errorBackend').addClass('d-none').text('');
        $('#usuario_id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#email').val($(this).data('email'));
        $('#rol').val($(this).data('rol'));

        // mostrar checkbox, ocultar password
        $('#chkCambiarPasswordContainer').show();
        $('#chkCambiarPassword').prop('checked', false);
        $('#contenedorPassword').addClass('d-none');
        $('#password, #password_confirmation').prop('required', false).val('');

        $('#errorPassword').addClass('d-none');
        $('#errorBackend').addClass('d-none').text('');

        $('#tituloModal').text('Editar usuario');
        modal.show();
    });
    $('#chkCambiarPassword').on('change', function () {
        if ($(this).is(':checked')) {
            $('#contenedorPassword').removeClass('d-none');
            $('#password, #password_confirmation').prop('required', true);
        } else {
            $('#contenedorPassword').addClass('d-none');
            $('#password, #password_confirmation')
                    .prop('required', false)
                    .val('');
            $('#errorPassword').addClass('d-none');
        }
    });
</script>
@endpush

