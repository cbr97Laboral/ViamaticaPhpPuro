<?php
require_once '../../Controlador/LoginRegistro/LoginController.php';
require_once '../../Rutas/Rutas.php';

$loginController = new LoginController();

$loginController->cerrarSesion();
?>
