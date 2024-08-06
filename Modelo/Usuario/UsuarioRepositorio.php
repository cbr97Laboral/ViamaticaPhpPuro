<?php
require_once '../../Entorno/BD/BasedeDatos.php';
require_once '../../Rutas/Rutas.php';
require_once '../../Modelo/Parametros/ParametrosRepositorio.php';

class UsuarioRepositorio
{
    public function agregarUsuario()
    {
        // para encriptado de contraseña
        $hashedPassword = password_hash('$contrasena', PASSWORD_DEFAULT);
    }

    public function actualizarUsuario()
    {
    }

    public function listarUsuarios()
    {
    }

    public function eliminarUsuario()
    {
    }

    //Consultas
    private function ObtenerGenericoDatosUsuario($identificador, string $columnas):Array{

        if (strpos($identificador, '@') !== false) {
            $columna = 'Correo';
        } elseif (is_numeric($identificador)) {
            $columna = 'idUsuario';
        } else {
            $columna = 'UserName';
        }

        $columnas = " ".$columnas." ";

        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT $columnas FROM Usuarios WHERE $columna = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $identificador);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            return $datos;
        }

        BaseDeDatos::desconectar();

        return [];
    }

    public function ObtenerIdUsuario(string $usuario_correo): int
    {
        $datos = $this->ObtenerGenericoDatosUsuario($usuario_correo, "idUsuario");
        if ($datos !==[]) {
            return $datos['idUsuario'];
        }

        return -1;
    }

    public function ObtenerContraseña(int $idUsuario): string
    {
        $datos = $this->ObtenerGenericoDatosUsuario($idUsuario, "Contrasena");
        if ($datos !==[]) {
            return $datos['Contrasena'];
        }
        return "";
    }

    public function ObtenerEstadosControl(int $idUsuario): array
    {
        $datos = $this->ObtenerGenericoDatosUsuario($idUsuario, "SesionActiva, Bloqueado");
        if ($datos !==[]) {
            return $datos;
        }

        return [];
    }

    public function ObtenerDatosBasicosUsuarioPersona(int $idUsuario): array
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT U.idUsuario, U.UserName, U.Correo, P.Nombres, P.Apellidos, P.Identificacion
        FROM Usuarios U
        INNER JOIN Personas P ON 
        U.idPersona = P.idPersona WHERE idUsuario = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $idUsuario);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        BaseDeDatos::desconectar();

        return null;
    }

    //ACTUALIZACIONES
    private function actualizarUsuarioGenerico(int $idUsuario, $propiedad, $nuevoValorPropiedad): bool
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "UPDATE Usuarios SET $propiedad = :valorPropiedad WHERE idUsuario = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':valorPropiedad', $nuevoValorPropiedad);
        $stmt->bindParam(':usuario', $idUsuario);
        $resultado = $stmt->execute();

        BaseDeDatos::desconectar();

        return $resultado;
    }

    public function activarSesionUsuario(int $idUsuario, bool $activar): bool
    {
        return $this->actualizarUsuarioGenerico($idUsuario, "SesionActiva", $activar);
    }

    public function bloquearUsuario(int $idUsuario, bool $bloquear): bool
    {
        return $this->actualizarUsuarioGenerico($idUsuario, "Bloqueado", $bloquear);
    }
}
