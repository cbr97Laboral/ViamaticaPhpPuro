<?php
require_once '../../Entorno/BD/BasedeDatos.php';
class RolesRepositorio
{
    public function ObtenerRolesParaSelect(): array {
        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT idRol, NombreRol FROM roles";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        BaseDeDatos::desconectar();
        return $roles;
    }

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
        BaseDeDatos::desconectar();
        return $roles;
    }

    public function ObtenerNombreRol(int $idRol): array
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT NombreRol FROM roles WHERE idRol = :idRol";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idRol', $idRol);
        $stmt->execute();

        if ($stmt->rowCount() < 1) {
            BaseDeDatos::desconectar();
            return [];
        }
        $roles = $stmt->fetch(PDO::FETCH_ASSOC);
        BaseDeDatos::desconectar();
        return $roles;
    }

    public function ObtenerOpcionesRol(array $roles): array
    {
        if (empty($roles)) {
            return [];
        }

        $conexion = BaseDeDatos::conectar();

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

        $opcionsUsuario = implode(',', array_fill(0, count($idsOpcion), '?'));

        $sql = "SELECT DISTINCT NombreOpcion, Enlace FROM opcionesrol WHERE idOpcionRol IN ($opcionsUsuario)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute($idsOpcion);

        if ($stmt->rowCount() < 1) {
            BaseDeDatos::desconectar();
            return [];
        }

        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        BaseDeDatos::desconectar();
        return $opciones;
    }
}
