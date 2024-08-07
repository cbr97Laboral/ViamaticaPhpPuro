<?php
require_once '../../Controlador/Usuario/UsuarioController.php';
require_once '../../Rutas/Rutas.php';
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$identificacion = $_POST['identificacion'];
$username = $_POST['username'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['roles'];

$usuarioController = new UsuarioController();

try {
    $usuarioController->registrarCliente($nombres, $apellidos, $identificacion,$username, $contrasena,$rol);
} catch (Exception $e) {
    Rutas::irRegistrarUsuario();
    echo "Error: " . $e->getMessage();
}
?>
