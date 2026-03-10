<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">
        <form id="formEntrega">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Nueva Entrega</h5>
                </div>

                <div class="modal-body">

                    <input class="form-control mb-2" name="responsable" placeholder="Responsable">
                    <input type="date" class="form-control mb-3" name="fecha_entrega">

                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th width="120">Cantidad</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="productosEntrega"></tbody>
                    </table>

                    <button type="button" class="btn btn-secondary btn-sm" id="btnAgregarProducto">
                        Agregar producto
                    </button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Guardar Entrega</button>
                </div>

            </div>
        </form>
    </div>
</div>
