<?php
require_once __DIR__ . '/../../Modelo/Usuario/UsuarioRepositorio.php';
require_once __DIR__ . '/../../Modelo/Usuario/Usuario.php';
class UsuarioController
{
    private UsuarioRepositorio $user;

    public function __construct()
    {
        $this->user = new UsuarioRepositorio();
    }

    public function obtenerPersonaFiltro($idRol, $username, $nombres, $apellidos, $identificacion, $correo)
    {
        header('Content-Type: application/json');

        try {
            $usuarios = $this->user->obtenerPersonaFiltro($idRol, $username, $nombres, $apellidos, $identificacion, $correo);

            echo json_encode([
                'columnas' => [
                    ["data" => "idUsuario", "title" => "idUsuario"],
                    ["data" => "idPersona", "title" => "idPersona"],
                    ["data" => "UserName", "title" => "UserName"],
                    ["data" => "Correo", "title" => "Correo"],
                    ["data" => "Nombres", "title" => "Nombres"],
                    ["data" => "Apellidos", "title" => "Apellidos"],
                    ["data" => "SesionActiva", "title" => "SesionActiva"],
                    ["data" => "Bloqueado", "title" => "Bloqueado"],
                    ["data" => "NombreRol", "title" => "Rol"],
                ],
                'datos' => $usuarios
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ]);
        }
    }

    public function listarUsuariosParaDashboard()
    {
        header('Content-Type: application/json');

        try {
            $usuarios = $this->user->listarUsuariosParaDashboard();

            echo json_encode([
                'columnas' => [
                    ["data" => "idUsuario", "title" => "ID"],
                    ["data" => "UserName", "title" => "UserName"],
                    ["data" => "ContadorIntentos", "title" => "Intentos fallidos"],
                    ["data" => "SesionActiva", "title" => "SesionActiva"],
                    ["data" => "Bloqueado", "title" => "Bloqueado"],
                ],
                'datos' => $usuarios
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ]);
        }
    }


    public function registrarCliente($nombres, $apellidos, $identificacion, $username, $contrasena, $idRole)
    {
        $usurarioModelo = new Usuario($nombres, $apellidos, $identificacion, $username, $contrasena, false, false);

        session_start();
        try {
            $exito = $this->user->registrarCliente($usurarioModelo, $idRole);

            if ($exito) {
                $_SESSION['error'] = "Registro exitoso!";
            } else {
                $_SESSION['error'] = "Error al registrar.";
            }

            Rutas::irRegistrarUsuario();
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al registrar: " . $e->getMessage();
            Rutas::irRegistrarUsuario();
        }
    }

    public function actualizarCliente($idUsuario, $idPersona, $idRole, $nombres, $apellidos, $identificacion, $username, $contrasena,$sesionActiva, $bloqueo)
    {
        header('Content-Type: application/json');
        $usurarioModelo = new Usuario($nombres, $apellidos, $identificacion, $username, $contrasena, $sesionActiva, $bloqueo);
        try {
            $exito = $this->user->actualizarCliente($idPersona, $idUsuario, $idRole, $usurarioModelo);

            echo json_encode([
                'status' => $exito? 'EXITO': 'ERROR',
                'message' => $exito
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
