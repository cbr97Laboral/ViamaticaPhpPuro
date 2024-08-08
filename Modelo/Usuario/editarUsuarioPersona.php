<?php
require_once '../../Controlador/Usuario/UsuarioController.php';

$idUsuario = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : null;
$idPersona = isset($_POST['idPersona']) ? $_POST['idPersona'] : null;
$idRol = isset($_POST['idRol']) ? $_POST['idRol'] : null;

$nombres = isset($_POST['nombres']) ? $_POST['nombres'] : "";
$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : "";
$identificacion = isset($_POST['identificacion']) ? $_POST['identificacion'] : "";

$username = isset($_POST['username']) ? $_POST['username'] : null;
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : null;
$sesionActiva = isset($_POST['sesionActiva']) ? $_POST['sesionActiva'] : null;
$bloqueo = isset($_POST['bloqueado']) ? $_POST['bloqueado'] : null;

$usuarioController = new UsuarioController();
header('Content-Type: application/json');
echo $usuarioController->actualizarCliente($idUsuario, $idPersona, $idRol, $nombres, $apellidos, $identificacion, $username, $contrasena,$sesionActiva, $bloqueo);
?>
