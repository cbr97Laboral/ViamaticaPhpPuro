<?php
require_once '../../Entorno/BD/BasedeDatos.php';
require_once '../../Modelo/Parametros/ParametrosRepositorio.php';

class UsuarioRepositorio
{
    public function registrarCliente(Usuario $usurarioModelo, int $idRole): bool {
        $conexion = BaseDeDatos::conectar();
    
        try {
            $conexion->beginTransaction();

            $idPersona = $this->registrarPersona(
                $usurarioModelo->getNombres(), 
                $usurarioModelo->getApellidos(), 
                $usurarioModelo->getIdentificacion()
            );
    
            if ($idPersona) {
                $exito = $this->registrarUsuario(
                    $usurarioModelo->getUserName(), 
                    $usurarioModelo->getCorreo(), 
                    $usurarioModelo->getContraseña(), 
                    $idPersona
                );


                $this->registrarRolUsuario($this->ObtenerIdUsuario($usurarioModelo->getUserName()), $idRole);
    
                if ($exito) {
                    $conexion->commit();
                    return true;
                } else {
                    $conexion->rollBack();
                    return false;
                }
            } else {
                $conexion->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            throw new Exception("Error al registrar cliente: " . $e->getMessage());
        }
    }

    private function registrarPersona($nombres, $apellidos, $identificacion)
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "INSERT INTO personas (nombres, apellidos, identificacion) VALUES (:nombres, :apellidos, :identificacion)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':identificacion', $identificacion);

        if ($stmt->execute()) {
            return $conexion->lastInsertId();
        } else {
            BaseDeDatos::desconectar();
            throw new Exception("Error al registrar persona: " . $stmt->error);
        }
        BaseDeDatos::desconectar();
    }

    private function registrarUsuario($username, $correo, $contrasena, $idPersona)
    {
        $conexion = BaseDeDatos::conectar();
        $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (username, correo, contrasena, idPersona) VALUES (:username, :correo, :contrasena, :idPersona)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $contrasenaHash);
        $stmt->bindParam(':idPersona', $idPersona, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            BaseDeDatos::desconectar();
            throw new Exception("Error al registrar usuario: " . $stmt->error);
        }
        BaseDeDatos::desconectar();
    }

    private function registrarRolUsuario(int $idUsuario, int $idRol)
    {
        $conexion = BaseDeDatos::conectar();

        $sql = "INSERT INTO usuarios_roles (idUsuario, idRol) VALUES (:idUsuario, :idRol)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':idRol', $idRol, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            BaseDeDatos::desconectar();
            throw new Exception("Error al registrar rol al usuario: " . $stmt->error);
        }
        BaseDeDatos::desconectar();
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

    public static function ObtenerCorreoUnico(string $correo): string
    {

        try {
            $conexion = BaseDeDatos::conectar();
            $stmt = $conexion->prepare("CALL baseprueba.ObtenerCorreoValido(:inputCorreo, @outputCorreo)");
            $stmt->bindParam(':inputCorreo', $correo, PDO::PARAM_STR);
            $stmt->execute();

            $query = $conexion->query("SELECT @outputCorreo AS outputCorreo");
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['outputCorreo'])) {
                return $result['outputCorreo'];
            } else {
                throw new Exception("Error al obtener el correo único");
            }
        } catch (PDOException $e) {
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }

        BaseDeDatos::desconectar();
    }

    //Consultas
    private function ObtenerGenericoDatosUsuario($identificador, string $columnas): array
    {

        if (strpos($identificador, '@') !== false) {
            $columna = 'Correo';
        } elseif (is_numeric($identificador)) {
            $columna = 'idUsuario';
        } else {
            $columna = 'UserName';
        }

        $columnas = " " . $columnas . " ";

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
        if ($datos !== []) {
            return $datos['idUsuario'];
        }

        return -1;
    }

    public function ObtenerContraseña(int $idUsuario): string
    {
        $datos = $this->ObtenerGenericoDatosUsuario($idUsuario, "Contrasena");
        if ($datos !== []) {
            return $datos['Contrasena'];
        }
        return "";
    }

    public function ObtenerEstadosControl(int $idUsuario): array
    {
        $datos = $this->ObtenerGenericoDatosUsuario($idUsuario, "SesionActiva, Bloqueado");
        if ($datos !== []) {
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
