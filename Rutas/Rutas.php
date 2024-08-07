<?php
define('BASE_URL', 'http://localhost/ViamaticaPhpPuro/');
class Rutas {
    public static function irHome() {
        header("Location: " . BASE_URL . "Vista/Home/home.php");
        exit();
    }

    public static function irLogin() {
        header("Location: " . BASE_URL . "Vista/Login/login.php");
        exit();
    }

    public static function irRegistrarUsuario() {
        
        header("Location: " . BASE_URL . "Vista/GestorUsuarios/registrarUsuario.php");
        exit();
    }
}
