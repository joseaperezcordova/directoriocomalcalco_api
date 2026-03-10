<div class="modal fade" id="modalVenta" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle de venta</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Folio:</strong> <span id="mFolio"></span></p>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Producto</th>
              <th class="text-center">Cant.</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody id="mDetalle"></tbody>
        </table>

        <div class="text-end fw-bold">
            Total: <span id="mTotal"></span>
        </div>
      </div>
    </div>
  </div>
</div>
