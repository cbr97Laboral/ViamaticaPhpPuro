<?php
class BaseDeDatos{
    private static $conexion;

    const SERVIDOR ="localhost";
    const USUARIO ="root";
    const CLAVE ="";
    const BASE_DATOS="basePrueba";

    public static function conectar() {
        if (self::$conexion === null) {
            try {
                self::$conexion = new PDO(
                    "mysql:host=" . self::SERVIDOR . ";dbname=" . self::BASE_DATOS,
                    self::USUARIO,
                    self::CLAVE
                );
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $error) {
                return "Error: " . $error->getMessage();
            }
        }
        return self::$conexion;
    }

    public static function desconectar() {
        self::$conexion = null;
    }
}
?>