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
}
