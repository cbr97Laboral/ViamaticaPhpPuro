<?php
class Rutas {
    public static function irHome() {
        header("Location: /ViamaticaPhpPuro/Vista/Home/home.php");
        exit();
    }

    public static function irLogin() {
        header("Location: /ViamaticaPhpPuro/Vista/Login/login.php");
        exit();
    }
}
