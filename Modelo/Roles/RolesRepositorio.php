<?php
require_once '../../Entorno/BD/BasedeDatos.php';
class RolesRepositorio
{
    public function ObtenerIdRolUsuario(int $idUsuario): array
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT idRol FROM usuarios_roles WHERE idUsuario = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $idUsuario);
        $stmt->execute();

        if ($stmt->rowCount() < 1) {
            BaseDeDatos::desconectar();
            return [];
        }

        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        return $roles;
    }

    public function ObtenerOpcionesRol(array $roles): array
    {
        if (empty($roles)) {
            return [];
        }

        $conexion = BaseDeDatos::conectar();

        // Crear una lista de marcadores de posición para la consulta IN
        $rolesUsuario = implode(',', array_fill(0, count($roles), '?'));

        $sql = "SELECT DISTINCT idOpcionRol FROM roles_opcionesrol WHERE idRol IN ($rolesUsuario)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute($roles);

        if ($stmt->rowCount() < 1) {
            BaseDeDatos::desconectar();
            return [];
        }

        $opciones = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        BaseDeDatos::desconectar();
        return $opciones;
    }

    public function ObtenerNombreOpcionesRol(array $idsOpcion): array
    {
        if (empty($idsOpcion)) {
            return [];
        }

        $conexion = BaseDeDatos::conectar();

        // Crear una lista de marcadores de posición para la consulta IN
        $opcionsUsuario = implode(',', array_fill(0, count($idsOpcion), '?'));

        $sql = "SELECT DISTINCT NombreOpcion FROM opcionesrol WHERE idOpcionRol IN ($opcionsUsuario)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute($idsOpcion);

        if ($stmt->rowCount() < 1) {
            BaseDeDatos::desconectar();
            return [];
        }

        $opciones = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        BaseDeDatos::desconectar();
        return $opciones;
    }
}
