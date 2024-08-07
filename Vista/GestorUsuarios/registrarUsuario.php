<?php
include '../Componentes/layout.php';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
}
?>
<link rel="stylesheet" type="text/css" href="diseno.css">
<main class="contenidoPrincipal-registrar">
    <?php if (isset($error)) : ?>
        <p class="error-message-registrar"><?php echo $error ?></p>
    <?php endif; ?>
    <script src="cargaDatos.js"></script>
    <script src="validacionRegistrarUsuario.js"></script>

    <h1>Registrar Usuario</h1>
    <form class="registro-form" action="http://localhost/ViamaticaPhpPuro/Modelo/Usuario/RegistarModificarUsuario.php" method="POST">
        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="roles">Rol:</label>
                <select class="form-input" id="roles" name="roles" required>
                </select>
            </div>
            <p id="roles-error" class="error-message-registrar"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="username">Nick Usuario:</label>
                <input class="form-input" type="text" id="username" name="username" required minlength="8" maxlength="20" oninput="validarUsername(this)">
            </div>
            <p id="username-error" class="error-message-registrar"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="nombres">Nombres:</label>
                <input class="form-input" type="text" id="nombres" name="nombres" required maxlength="100" onchange="validarNombres(this)">
            </div>
            <p id="nombres-error" class="error-message-registrar"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="apellidos">Apellidos:</label>
                <input class="form-input" type="text" id="apellidos" name="apellidos" required maxlength="100" onchange="validarApellidos(this)">

            </div>
            <p id="apellidos-error" class="error-message-registrar"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="identificacion">Identificación:</label>
                <input class="form-input" type="text" id="identificacion" name="identificacion" required maxlength="10" oninput="validarIdentificacion(this)">
            </div>
            <p id="identificacion-error" class="error-message-registrar"></p>
        </div>

        <div class="container-campo">
            <div class="form-campo">
                <label class="form-label" for="contrasena">Contraseña:</label>
                <div>
                    <button type="button" id="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
                    <input class="form-input" type="password" id="contrasena" name="contrasena" required minlength="5" maxlength="15" oninput="validarContrasena(this)">
                </div>
            </div>
            <p id="contrasena-error" class="error-message-registrar"></p>
        </div>

        <button class="form-submit-button" type="submit">Registrar</button>
    </form>
</main>
</body>

</html>

<?php
if (isset($_SESSION['error'])) {
    $_SESSION['error'] = NULL;
}
?>