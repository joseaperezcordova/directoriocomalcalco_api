<div class="modal fade" id="modalUsuario">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tituloModal">Nuevo usuario</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formUsuario">
                @csrf
                <input type="hidden" id="usuario_id">
                <div class="modal-body">
                    <div id="errorBackend" class="alert alert-danger d-none"></div>
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="name" autocomplete="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Email/Usuario</label>
                        <input type="email" class="form-control" id="email" autocomplete="username" required>
                    </div>
                    <div class="mb-3">
                        <label>Rol</label>
                        <select class="form-control" id="rol" autocomplete="off" required>
                            <option value="">Selecciona un rol</option>
                            <option value="admin">Administrador</option>
                            <option value="encargado">Encargado</option>
                            <option value="vendedor">Vendedor</option>
                            <option value="seguridad">Seguridad</option>
                        </select>
                    </div>
                    <div class="form-check mb-3" id="chkCambiarPasswordContainer">
                        <input class="form-check-input" type="checkbox" id="chkCambiarPassword">
                        <label class="form-check-label" for="chkCambiarPassword">
                            Cambiar contraseña
                        </label>
                    </div>

                    <div id="contenedorPassword" class="d-none">
                        <div class="mb-3">
                            <label>Nueva contraseña</label>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label>Confirmar contraseña</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   autocomplete="new-password">
                            <small id="errorPassword" class="text-danger d-none">
                                Las contraseñas no coinciden
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>