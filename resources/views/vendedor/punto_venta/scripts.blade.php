@push('scripts')
<script>
    $(document).ready(function () {
        cargarCardsProductos();
        let carrito = [];

        function actualizarCarrito() {

    let tbody = $('#tabla-carrito tbody');
    tbody.empty();

    let total = 0;

    carrito.forEach((item, index) => {

        let subtotal = item.es_cortesia ? 0 : item.cantidad * item.precio;
        if (!item.es_cortesia) {
            total += subtotal;
        }

        tbody.append(`
            <tr>
                <td>${item.nombre} ${item.es_cortesia ? '<span class="badge bg-warning text-dark">Cortesía</span>' : ''}</td>
                <td>${item.cantidad}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button class="btn btn-sm btn-secondary btn-cortesia" data-index="${index}">
                        🎁
                    </button>
                    <button class="btn btn-sm btn-danger btn-eliminar" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    $('#total').text(total.toFixed(2));
}

        // Agregar producto
        // Agregar producto (delegación de eventos para funcionar con tarjetas nuevas)
        $(document).on('click', '.btn-agregar', function () {

            let id = $(this).data('id');
            let nombre = $(this).data('nombre');
            let precio = parseFloat($(this).data('precio'));
            let stock = parseInt($(this).data('stock'));

            let existe = carrito.find(p => p.id == id);

            if (existe) {

                if (existe.cantidad + 1 > stock) {
                    Swal.fire('Atención', 'Stock insuficiente', 'warning');
                    return;
                }

                existe.cantidad++;

            } else {

                if (stock <= 0) {
                    Swal.fire('Atención', 'Producto sin existencia', 'warning');
                    return;
                }

                carrito.push({
    id: id,
    nombre: nombre,
    precio: precio,
    cantidad: 1,
    stock: stock,
    es_cortesia: 0
});
            }

            actualizarCarrito();
        });

        // Eliminar producto
        $(document).on('click', '.btn-eliminar', function () {
            let index = $(this).data('index');
            carrito.splice(index, 1);
            actualizarCarrito();
        });
$(document).on('click', '.btn-cortesia', function () {
    let index = $(this).data('index');
    carrito[index].es_cortesia = carrito[index].es_cortesia ? 0 : 1;
    actualizarCarrito();
});
        // Procesar pago
        $('#btn-pagar').click(function () {

            if (carrito.length === 0) {
                Swal.fire('Atención', 'Agrega productos', 'warning');
                return;
            }

            $.ajax({
                url: "{{ route('vendedor.punto-venta.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    carrito: carrito,
                    forma_pago: $('input[name="forma_pago"]:checked').val()
                },
                beforeSend: function () {
                    $('#btn-pagar').prop('disabled', true);
                },
                success: function (resp) {
                    Swal.fire('Venta registrada', 'Venta registrada correctamente', 'success');
                    carrito = [];
                    actualizarCarrito();
                    cargarCardsProductos();
                },
                error: function () {
                    Swal.fire('Atención', 'No se pudo registrar la venta', 'warning');
                },
                complete: function () {
                    $('#btn-pagar').prop('disabled', false);
                }
            });

        });
        function cargarCardsProductos() {

            $.ajax({
                url: "{{ route('vendedor.punto-venta.data') }}",
                method: "GET",
                success: function (resp) {

                    let contenedor = $('.row.g-3'); // Contenedor donde están las cards
                    contenedor.empty(); // Limpia todas las cards

                    resp.productos.forEach(prod => {

                        contenedor.append(`
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card h-100 shadow-sm border-0 producto-card">
                            <div class="card-body text-center d-flex flex-column justify-content-between">

                                <div>
                                    <h6 class="fw-bold mb-2">${prod.nombre}</h6>

                                    <h5 class="text-primary fw-bold">
                                        $${parseFloat(prod.precio).toFixed(2)}
                                    </h5>
                                </div>

                                <button class="btn btn-primary btn-lg w-100 mt-3 btn-agregar"
                                    data-id="${prod.id}"
                                    data-nombre="${prod.nombre}"
                                    data-precio="${prod.precio}"
                                    data-stock="${prod.cantidad}"
                                    ${prod.cantidad <= 0 ? 'disabled' : ''}>
                                    ${prod.cantidad <= 0 ? 'Sin stock' : 'Agregar'}
                                </button>

                                <small class="text-muted d-block mt-2">
                                    Stock: ${prod.cantidad}
                                </small>

                            </div>
                        </div>
                    </div>
                `);
                    });
                }
            });
        }
    });
</script>
@endpush