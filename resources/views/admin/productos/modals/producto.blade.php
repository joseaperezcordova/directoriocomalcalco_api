<div class="modal fade" id="modalProducto">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tituloModal">Nuevo producto</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formProducto">
                @csrf
                <input type="hidden" id="producto_id">

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio" required>
                    </div>

                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" class="form-control" id="stock" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>