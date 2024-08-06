<?php
class ControlIntentosRepositorio{
    public function ObtenerIntentosUsuario(int $idUsuario): int
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT ContadorIntentos FROM controllogin WHERE idUsuario = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $idUsuario);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            return $datos['ContadorIntentos'];
        }

        BaseDeDatos::desconectar();

        return -1;
    }

    public function ActualizarIntentosUsuario(int $idUsuario, int $nuevoContadorIntentos): bool
    {
        $conexion = BaseDeDatos::conectar();
        $sql = "UPDATE controllogin SET ContadorIntentos = :contadorIntentos WHERE idUsuario = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':contadorIntentos', $nuevoContadorIntentos, PDO::PARAM_INT);
        $stmt->bindParam(':usuario', $idUsuario, PDO::PARAM_STR);
        $resultado = $stmt->execute();

        BaseDeDatos::desconectar();

        return $resultado;
    }
}
?>