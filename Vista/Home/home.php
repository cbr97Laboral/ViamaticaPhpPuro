<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    require_once '../Rutas/Rutas.php';
    Rutas::irLogin();
}

$usuario = $_SESSION['usuario'];

$opciones = $_SESSION['opcionesRol'];
print_r($opciones);
$ultimaSesion = $_SESSION['historialSesion'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="diseno.css">
</head>

<body class="contenedorPrincipal">
    <nav>
        <a href="/ViamaticaPhpPuro/Modelo/Login/CerrarSesion.php" class="logout-button">Cerrar sesión</a>

        <?php foreach ($opciones as $opcion) : ?>
            <a href=""><?php echo $opcion; ?></a>
        <?php endforeach; ?>
    </nav>

    <main class="contenidoPrincipal">
        <h1>Bienvenido</h1>

        <p><strong>Usuario:</strong><?php echo $usuario['UserName'] ?></p>
        <p><strong>Correo:</strong><?php echo $usuario['Correo'] ?></p>
        <p><strong>Nombres:</strong><?php echo $usuario['Nombres'] ?></p>
        <p><strong>Apellidos:</strong><?php echo $usuario['Apellidos'] ?></p>
        <p><strong>Identificación:</strong><?php echo $usuario['Identificacion'] ?></p>

        <?php if (isset($ultimaSesion)) : ?>
            <h2>Última Sesión</h2>
            <p><strong>Fecha de Ingreso:</strong><?php echo $ultimaSesion['FechaIngreso'] ?></p>
            <p><strong>Fecha de Cierre:</strong><?php echo $ultimaSesion['FechaCierre'] ?></p>
        <?php endif; ?>
    </main>

</body>

</html>