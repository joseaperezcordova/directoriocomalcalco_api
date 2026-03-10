<div class="modal fade" id="modalEntregar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-box-open me-2"></i> Entregar Producto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- ID Recepción -->
                <div class="mb-3">
                    <label class="form-label fw-bold">ID Recepción Detalle</label>
                    <input type="text" id="recepcion_id_visible" class="form-control" readonly>
                    <input type="hidden" id="recepcion_id">
                </div>

                <!-- Producto -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Producto</label>
                    <input type="text" id="producto_nombre" class="form-control" readonly>
                </div>

                <!-- Disponible -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-success">
                        Disponible para entregar
                    </label>
                    <input type="text" id="cantidad_disponible" 
                           class="form-control bg-light text-success fw-bold" readonly>
                </div>

                <!-- Cantidad a entregar -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Cantidad a entregar</label>
                    <input type="number" 
                           id="cantidad_entregar" 
                           class="form-control"
                           min="1">
                    <div class="invalid-feedback">
                        La cantidad supera lo disponible.
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-secondary" 
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" 
                        class="btn btn-primary" 
                        id="confirmarEntrega">
                    Guardar Entrega
                </button>
            </div>

        </div>
    </div>
</div>