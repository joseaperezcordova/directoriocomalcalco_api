<div class="modal fade" id="modalRecepcion">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6>Nueva Recepción</h6>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col">
                        <select id="producto" class="form-control"></select>
                    </div>
                    <div class="col-3">
                        <input type="number" id="cantidad" class="form-control" min="1">
                    </div>
                    <div class="col-2">
                        <button class="btn btn-primary" id="agregar">+</button>
                    </div>
                </div>

                <hr>

                <table class="table table-sm" id="lista">
                    <thead>
                        <tr><th>Producto</th><th>Cantidad</th><th></th></tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <strong>Total: <span id="total">0</span></strong>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="guardar">Guardar</button>
            </div>

        </div>
    </div>
</div>
