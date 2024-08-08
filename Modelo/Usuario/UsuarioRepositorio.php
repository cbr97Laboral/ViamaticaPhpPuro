<?php
require_once '../../Entorno/BD/BasedeDatos.php';
require_once '../../Modelo/Parametros/ParametrosRepositorio.php';

class UsuarioRepositorio
{
    public function obtenerPersonaFiltro($idRol, $username, $nombres, $apellidos, $identificacion, $correo)
    {
        $conexion = BaseDeDatos::conectar();

        $parametros = [];
        $condiciones = [];

        // Condiciones para Usuarios
        if ($username) {
            $condiciones[] = "U.UserName LIKE :username";
            $parametros[':username'] = '%' . $username . '%';
        }

        if ($correo) {
            $condiciones[] = "U.Correo LIKE :correo";
            $parametros[':correo'] = '%' . $correo . '%';
        }

        // Condiciones para Personas
        if ($nombres) {
            $condiciones[] = "P.Nombres LIKE :nombres";
            $parametros[':nombres'] = '%' . $nombres . '%';
        }

        if ($apellidos) {
            $condiciones[] = "P.Apellidos LIKE :apellidos";
            $parametros[':apellidos'] = '%' . $apellidos . '%';
        }

        if ($identificacion) {
            $condiciones[] = "P.Identificacion LIKE :identificacion";
            $parametros[':identificacion'] = '%' . $identificacion . '%';
        }

        // Condición para el rol si está presente
        if ($idRol) {
            $condiciones[] = "UR.idRol = :idRol";
            $parametros[':idRol'] = $idRol;
        }
        $condicionWhere = "";
        if (count($condiciones) > 0) {
            $condicionWhere = " WHERE " . implode(" AND ", $condiciones);
        }

        $sql = "
        SELECT 
            U.idUsuario,
            P.idPersona,
            U.UserName,
            U.Correo,
            P.Nombres,
            P.Apellidos,
            P.Identificacion,
            U.SesionActiva,
            U.Bloqueado,
            R.NombreRol,
            R.idRol
        FROM Usuarios U
        INNER JOIN personas P ON U.idPersona = P.idPersona
        INNER JOIN usuarios_roles UR ON U.idUsuario = UR.idUsuario
        INNER JOIN roles R ON UR.idRol = R.idRol
        $condicionWhere";

        $stmt = $conexion->prepare($sql);
        $stmt->execute($parametros);

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        BaseDeDatos::desconectar();

        return $usuarios;
    }

    public function listarUsuariosParaDashboard()
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "
        SELECT 
            U.idUsuario,
            U.UserName,
            CL.ContadorIntentos,
            U.SesionActiva,
            U.Bloqueado
        FROM Usuarios U
        INNER JOIN Personas P ON U.idPersona = P.idPersona
        INNER JOIN ControlLogin CL ON U.idUsuario = CL.idUsuario";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        BaseDeDatos::desconectar();

        return $usuarios;
    }

    public function registrarCliente(Usuario $usurarioModelo, int $idRole): bool
    {
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

                if ($exito) {
                    $idUsuario = $this->ObtenerIdUsuario($usurarioModelo->getUserName());
                    $this->registrarRolUsuario($idUsuario, $idRole);
                    $conexion->commit();
                    $this->CrearIntentosUsuario($idUsuario);

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
        } finally {
            BaseDeDatos::desconectar();
        }
    }

    public function actualizarCliente($idPersona, $idUsuario, int $idRole, Usuario $usurarioModelo): bool
    {
        $conexion = BaseDeDatos::conectar();

        try {
            $conexion->beginTransaction();

            $this->actualizarPersona(
                $idPersona,
                $usurarioModelo->getNombres(),
                $usurarioModelo->getApellidos(),
                $usurarioModelo->getIdentificacion()
            );

            $contra = $usurarioModelo->getContraseña()? null: $usurarioModelo->getContraseña();

            $this->actualizarUsuario(
                $idUsuario,
                $usurarioModelo->getUserName(),
                $usurarioModelo->getCorreo(),
                $contra,
                $usurarioModelo->isSesionActiva(),
                $usurarioModelo->isBloqueado(),
                $idPersona
            );

            $this->actualizarRolUsuario($idUsuario, $idRole);
            $conexion->commit();

            return true;
        } catch (Exception $e) {
            $conexion->rollBack();
            //return false;
            throw new Exception("Error al registrar cliente: " . $e->getMessage());
        } finally {
            BaseDeDatos::desconectar();
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

    private function actualizarPersona($idPersona,$nombres, $apellidos, $identificacion)
    {
        $conexion = BaseDeDatos::conectar();
        $params = [];
        $params[':idPersona'] = $idPersona;

        $columnasActualizar = "";
        if ($nombres !== null) {
            $columnasActualizar .= " nombres = :nombres,";
            $params[':nombres'] = $nombres;
        }

        if ($apellidos !== null) {
            $columnasActualizar .= " apellidos = :apellidos,";
            $params[':apellidos'] = $apellidos;
        }

        if ($identificacion !== null) {
            $columnasActualizar .= " identificacion = :identificacion,";
            $params[':identificacion'] = $identificacion;
        }

        $columnasActualizar = rtrim($columnasActualizar, ',');

        $sql = "UPDATE personas SET " . $columnasActualizar . " WHERE idPersona = :idPersona";

        try {
            $stmt = $conexion->prepare($sql);
            if ($stmt->execute($params)) {
                return true;
            } else {
                throw new Exception("Error al actualizar persona: " . $stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            BaseDeDatos::desconectar();
        }
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

    private function actualizarUsuario($idUsuario, $username = null, $correo = null, $contrasena = null, $sesionActiva = null, $bloqueo = null, $idPersona = null)
    {
        $conexion = BaseDeDatos::conectar();
        $params = [];
        $params[':idUsuario'] = $idUsuario;

        $columnasActualizar = "";
        if ($username !== null) {
            $columnasActualizar .= " username = :username,";
            $params[':username'] = $username;
        }
        if ($correo !== null) {
            $columnasActualizar .= " correo = :correo,";
            $params[':correo'] = $correo;
        }
        if ($contrasena !== null) {
            $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);
            $columnasActualizar .= " contrasena = :contrasena,";
            $params[':contrasena'] = $contrasenaHash;
        }
        if ($sesionActiva !== null) {
            $columnasActualizar .= " SesionActiva = :sesionActiva,";
            $params[':sesionActiva'] = $sesionActiva;
        }
        if ($bloqueo !== null) {
            $columnasActualizar .= " Bloqueado = :bloqueo,";
            $params[':bloqueo'] = $bloqueo;
        }
        if ($idPersona !== null) {
            $columnasActualizar .= " idPersona = :idPersona,";
            $params[':idPersona'] = $idPersona;
        }

        $columnasActualizar = rtrim($columnasActualizar, ',');

        $sql = "UPDATE usuarios SET " . $columnasActualizar . " WHERE idUsuario = :idUsuario";

        try {
            $stmt = $conexion->prepare($sql);
            if ($stmt->execute($params)) {
                return true;
            } else {
                throw new Exception("Error al actualizar usuario: " . $stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            BaseDeDatos::desconectar();
        }
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

    private function actualizarRolUsuario(int $idUsuario, int $idRol)
    {
        $conexion = BaseDeDatos::conectar();
        $params = [];
        $params[':idUsuario'] = $idUsuario;

        $columnasActualizar = "";
        if ($idRol !== null) {
            $columnasActualizar .= " idRol = :idRol,";
            $params[':idRol'] = $idRol;
        }

        $columnasActualizar = rtrim($columnasActualizar, ',');

        $sql = "UPDATE usuarios_roles SET " . $columnasActualizar . " WHERE idUsuario = :idUsuario";

        try {
            $stmt = $conexion->prepare($sql);
            if ($stmt->execute($params)) {
                return true;
            } else {
                throw new Exception("Error al actualizar usuarios_roles: " . $stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            BaseDeDatos::desconectar();
        }
    }

    public function CrearIntentosUsuario(int $idUsuario): bool
    {
        $conexion = BaseDeDatos::conectar();

        $sql = "INSERT INTO controllogin (idUsuario) VALUES (:idUsuario)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $resultado = $stmt->execute();
        BaseDeDatos::desconectar();

        return $resultado;
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
