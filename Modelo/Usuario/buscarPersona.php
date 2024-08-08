<?php
require_once '../../Controlador/Usuario/UsuarioController.php';
require_once '../../Rutas/Rutas.php';

$idRol = isset($_GET['idRol']) ? $_GET['idRol'] : null;
$username = isset($_GET['username']) ? $_GET['username'] : null;
$nombres = isset($_GET['nombres']) ? $_GET['nombres'] : null;
$apellidos = isset($_GET['apellidos']) ? $_GET['apellidos'] : null;
$identificacion = isset($_GET['identificacion']) ? $_GET['identificacion'] : null;
$correo = isset($_GET['correo']) ? $_GET['correo'] : null;

$usuarioController = new UsuarioController();

header('Content-Type: application/json');
$usuarioController = new UsuarioController();
echo $usuarioController->obtenerPersonaFiltro($idRol, $username, $nombres, $apellidos, $identificacion, $correo);
?>
