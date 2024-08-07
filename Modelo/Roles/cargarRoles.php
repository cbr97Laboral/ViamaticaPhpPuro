<?php
require_once '../../Controlador/Roles/RolesController.php';

$controller = new RolesController();
header('Content-Type: application/json');
$roles = $controller->obtenerRolesParaSelect();
echo $roles;
?>
