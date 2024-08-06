<?php
session_start();

if (isset($_SESSION['usuario'])) {
    require_once '../../Rutas/Rutas.php'; 
    Rutas::irHome();
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="diseno.css">
</head>

<body class="principal-container">
    <main class="login-container">
        <h1 class="login-title">Iniciar sesion</h1>
        <form class="login-form" action='/ViamaticaPhpPuro/Modelo/Login/IniciarSesion.php' method="post">

            <div class="form-campo">
                <label class="form-label" for="username">Usuario o Correo:</label>
                <input class="form-input" type="text" name="username" id="username">
            </div>

            <div class="form-campo">
                <label class="form-label" for="password">Contraseña:</label>
                <input class="form-input" type="password" name="contrasena" id="contrasena">
            </div>

            <input class="form-submit-button" type="submit" value="Iniciar Sesión">
        </form>
        <?php if (isset($error)) : ?>
            <p class="error-message"><?php echo $error ?></p>
        <?php endif; ?>
    </main>
</body>

</html>

<?php
$_SESSION['error']=null;
?>