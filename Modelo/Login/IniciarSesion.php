<?php
require_once '../../Controlador/LoginRegistro/LoginController.php';
require_once '../../Rutas/Rutas.php';

$loginController = new LoginController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_correo = $_POST['username'];
    $contrasena = $_POST['contrasena'];
    
    $loginController->iniciarSesion($usuario_correo, $contrasena);
} else {
    Rutas::irLogin();
}
?>
