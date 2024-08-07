<?php
require_once '../../Controlador/Usuario/UsuarioController.php';
$usuarioController = new UsuarioController();
echo $usuarioController->listarUsuariosParaDashboard();
?>
