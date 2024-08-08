<?php
include '../Componentes/layout.php';
?>
<link rel="stylesheet" type="text/css" href="disenoConsultar.css">
<link rel="stylesheet" type="text/css" href="modal.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>

<script src="cargaDatos.js"></script>
<script src="datatable.js"></script>
<script src="validacionConsultarPersonaUsuario.js"></script>
<script src="validacionModal.js?V1"></script>
<script src="editarUsuario.js"></script>
<main class="contenidoPrincipal-consultarPersona">
    <h1>Consultar persona</h1>
    <form class="consultar-persona-form">
        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="roles">Rol:</label>
                <select class="form-input" id="roles" name="roles">
                </select>
            </div>
            <p id="roles-error" class="error-message-consulta"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="username">Nick Usuario:</label>
                <input class="form-input" type="text" id="username" name="username" minlength="8" maxlength="20" oninput="validarUsername(this)">
            </div>
            <p id="username-error" class="error-message-consulta"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="nombres">Nombres:</label>
                <input class="form-input" type="text" id="nombres" name="nombres" maxlength="100" onchange="validarNombres(this)">
            </div>
            <p id="nombres-error" class="error-message-consulta"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="apellidos">Apellidos:</label>
                <input class="form-input" type="text" id="apellidos" name="apellidos" maxlength="100" onchange="validarApellidos(this)">

            </div>
            <p id="apellidos-error" class="error-message-consulta"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="identificacion">Identificación:</label>
                <input class="form-input" type="text" id="identificacion" name="identificacion" maxlength="10" oninput="validarIdentificacion(this)">
            </div>
            <p id="identificacion-error" class="error-message-consulta"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="correo">Correo:</label>
                <input class="form-input" type="text" id="correo" name="correo">
            </div>
            <p id="identificacion-error" class="error-message-consulta"></p>
        </div>

        <button class="form-submit-button" type="submit">Buscar</button>
    </form>
    <table id="datosUsuarios" class="display" style="width:100%">
    </table>

    <div class="modal-overlay" id="modal" name="modal">
        <form id="form-modal" name="form-modal" class="modal-container">
            <h1>Editar datos</h1>
            <input type="hidden" id="idUsuario-modal" name="idUsuario-modal" disabled>
            <input type="hidden" id="idPersona-modal" name="idPersona-modal" disabled>
            <div class="container-campo">
                <div class="form-campo">
                    <label class="form-label" for="roles">Rol:</label>
                    <select class="form-input" id="roles-modal" name="roles-modal" required>
                    </select>
                </div>
                <p id="roles-modal-error" class="error-message-modal"></p>
            </div>

            <div class="container-campo">
                <div class="form-campo">
                    <label class="form-label" for="username">Nick Usuario:</label>
                    <input class="form-input" type="text" id="username-modal" name="username-modal" required minlength="8" maxlength="20" oninput="validarModalUsername(this)">
                </div>
                <p id="username-modal-error" class="error-message-modal"></p>
            </div>

            <div class="container-campo">
                <div class="form-campo">
                    <label class="form-label" for="nombres">Nombres:</label>
                    <input class="form-input" type="text" id="nombres-modal" name="nombres-modal" required maxlength="100" onchange="validarModalNombres(this)">
                </div>
                <p id="nombres-modal-error" class="error-message-modal"></p>
            </div>

            <div class="container-campo">
                <div class="form-campo">
                    <label class="form-label" for="apellidos">Apellidos:</label>
                    <input class="form-input" type="text" id="apellidos-modal" name="apellidos-modal" required maxlength="100" onchange="validarModalApellidos(this)">

                </div>
                <p id="apellidos-modal-error" class="error-message-modal"></p>
            </div>

            <div class="container-campo">
                <div class="form-campo">
                    <label class="form-label" for="identificacion">Identificación:</label>
                    <input class="form-input" type="text" id="identificacion-modal" name="identificacion-modal" required maxlength="10" oninput="validarModalIdentificacion(this)">
                </div>
                <p id="identificacion-modal-error" class="error-message-modal"></p>
            </div>

            <div class="container-campo">
                <div class="form-campo">
                    <label class="form-label" for="contrasena">Contraseña:</label>
                    <div>
                        <button type="button" id="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
                        <input class="form-input" type="password" id="contrasena-modal" name="contrasena-modal" maxlength="20" oninput="validarModalContrasena(this)">
                    </div>
                </div>
                <p id="contrasena-modal-error" class="error-message-modal"></p>
            </div>

            <div class="estados-modal">
                <div class="container-campo">
                    <div class="form-campo">
                        <label class="form-label" for="sesion-modal">Sesion activa:</label>
                        <select class="form-input" id="sesion-modal" name="sesion-modal" required>
                        </select>
                    </div>
                    <p id="sesion-modal-error" class="error-message-modal"></p>
                </div>

                <div class="container-campo">
                    <div class="form-campo">
                        <label class="form-label" for="bloqueado-modal">Bloqueado:</label>
                        <select class="form-input" id="bloqueado-modal" name="bloqueado-modal" required>
                        </select>
                    </div>
                    <p id="bloqueado-modal-error" class="error-message-modal"></p>
                </div>
            </div>

            <button class="form-submit-button" type="submit">Guardar</button>
            <button class="form-submit-button" type="button" onclick="limpiarModal()">Cerrar</button>
        </form>
    </div>

    <script>
        document.getElementById('form-modal').addEventListener('submit', function(event) {
            event.preventDefault();
            editarUsuarioModal();
        });
    </script>
</main>