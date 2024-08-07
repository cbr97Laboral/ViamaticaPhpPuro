<?php
require_once __DIR__ . '/../../Modelo/Roles/RolesRepositorio.php';
class RolesController{
    private RolesRepositorio $roles;

    public function __construct()
    {
        $this->roles = new RolesRepositorio();
    }

    public function obtenerRolesParaSelect() {
        header('Content-Type: application/json');
        
        try {
            $roles = $this->roles->ObtenerRolesParaSelect();
            return json_encode([
                'status' => 'success',
                'data' => $roles
            ]);
        } catch (Exception $e) {

            return json_encode([
                'status' => 'error',
                'message' => 'Error al obtener roles: ' . $e->getMessage()
            ]);
        }
    }
}
?>