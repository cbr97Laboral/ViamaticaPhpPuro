<?php
require_once '../../Entorno/BD/BasedeDatos.php'; 
class ParametrosRepositorio{

    private static function obtenerParametro(string $codigoParametro): string {
        $conexion = BaseDeDatos::conectar();
        $sql = "SELECT Valor FROM parametros WHERE Parametro = :parametro";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':parametro', $codigoParametro);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $valor = $stmt->fetch(PDO::FETCH_ASSOC);
            return $valor['Valor'];
        }

        BaseDeDatos::desconectar();

        return "";
    }

    public static function obtenerIntentos(): int{
        return (int) self::obtenerParametro('IntentosLogin');
    }
}
?>