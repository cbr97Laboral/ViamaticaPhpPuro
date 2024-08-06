<?php
require_once '../../Entorno/BD/BasedeDatos.php';
class ObtenerNombreOpcionesRol{
    public function registrarInicioSesion(int $idUsuario) {
        $conexion = BaseDeDatos::conectar();
        $fechaActual = date('Y-m-d H:i:s');
        $sql = "INSERT INTO historialsesiones (idUsuario, FechaIngreso) VALUES (:idUsuario, :fechaIngreso)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':fechaIngreso', $fechaActual);
        $stmt->execute();
    }
    
    public function registrarCierreSesion(int $idUsuario) {
        $conexion = BaseDeDatos::conectar();
        $fechaActual = date('Y-m-d H:i:s');
        $sql = "SELECT FechaIngreso FROM historialsesiones WHERE idUsuario = :idUsuario AND FechaCierre IS NULL ORDER BY FechaIngreso DESC LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $fechaIngreso = $stmt->fetch(PDO::FETCH_ASSOC)['FechaIngreso'];
            $sqlUpdate = "UPDATE historialsesiones SET FechaCierre = :fechaCierre WHERE idUsuario = :idUsuario AND FechaIngreso = :fechaIngreso";
            $stmtUpdate = $conexion->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':fechaCierre', $fechaActual);
            $stmtUpdate->bindParam(':idUsuario', $idUsuario);
            $stmtUpdate->bindParam(':fechaIngreso', $fechaIngreso);
            $stmtUpdate->execute();
        }
    }

    public function obtenerUltimaSesionCerrada(int $idUsuario): ?array {
        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT FechaIngreso, FechaCierre 
                FROM historialsesiones 
                WHERE idUsuario = :idUsuario AND FechaCierre IS NOT NULL 
                ORDER BY FechaIngreso DESC 
                LIMIT 1";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
    
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        return null;
    }
}
?>