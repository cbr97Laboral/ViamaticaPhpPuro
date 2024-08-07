<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    require_once '../Rutas/Rutas.php';
    Rutas::irLogin();
}
require_once '../../Rutas/Rutas.php';
$usuario = $_SESSION['usuario'];
$opciones = $_SESSION['opcionesRol'];
$ultimaSesion = $_SESSION['historialSesion'];

include '../Componentes/nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhpPuro</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>Vista/Home/diseno.css?v2">
</head>
<body class="contenedorPrincipal">
