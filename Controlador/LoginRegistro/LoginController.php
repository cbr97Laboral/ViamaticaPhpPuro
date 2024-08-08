<?php
class LoginController
{
    public function iniciarSesion(string $usuario_correo, string $contrasena)
    {
        require_once '../../Modelo/Usuario/UsuarioRepositorio.php';
        require_once '../../Modelo/ControlIntentos/ControlIntentosRepositorio.php';
        require_once '../../Modelo/Roles/RolesRepositorio.php';
        require_once '../../Modelo/ControlSesiones/ControlSesionRepositorio.php';

        $usuario_correo = htmlspecialchars($usuario_correo);
        $contrasena = htmlspecialchars($contrasena);

        $user = new UsuarioRepositorio();

        session_start();
        $idUsuario = $user->ObtenerIdUsuario($usuario_correo);

        $limiteIntentos = ParametrosRepositorio::obtenerIntentos();
        $this->validacionesLogin($idUsuario, $limiteIntentos, $contrasena); //Redirecciona a login si no se cumplen los criterios.

        $user->activarSesionUsuario($idUsuario, true);

        $controlIntentosRepo = new ControlIntentosRepositorio();
        $controlIntentosRepo->ActualizarIntentosUsuario($idUsuario, 0);
        $_SESSION['usuario'] = $user->ObtenerDatosBasicosUsuarioPersona($idUsuario);

        $rolesRepo = new RolesRepositorio();
        $idRoles = $rolesRepo->ObtenerIdRolUsuario($idUsuario);
        $idsOpciones = $rolesRepo->ObtenerOpcionesRol($idRoles);
        $_SESSION['opcionesRol'] = $rolesRepo->ObtenerNombreOpcionesRol($idsOpciones);
        $_SESSION['nombreRol'] = $rolesRepo->ObtenerNombreRol($idRoles[0]) ;
        $historialSesionRepo = new ObtenerNombreOpcionesRol();
        $historialSesionRepo->registrarInicioSesion($idUsuario);

        $_SESSION['historialSesion'] = $historialSesionRepo->obtenerUltimaSesionCerrada($idUsuario);
        Rutas::irHome();
    }

    public function cerrarSesion()
    {
        require_once '../../Rutas/Rutas.php';
        require_once '../../Modelo/Usuario/UsuarioRepositorio.php';
        require_once '../../Modelo/ControlSesiones/ControlSesionRepositorio.php';
        session_start();

        $usuarioRepo = new UsuarioRepositorio();
        $idUsuario = $_SESSION['usuario']['idUsuario'];
        $usuarioRepo->activarSesionUsuario($idUsuario, false);

        $historialSesionRepo = new ObtenerNombreOpcionesRol();
        $historialSesionRepo->registrarCierreSesion($idUsuario);

        session_unset();
        session_destroy();
        
        Rutas::irLogin();
    }

    public function validacionesLogin(int $idUsuario, int $limiteIntentos,string $contrasena)
    {
        $user = new UsuarioRepositorio();
        $msj ="Nombre de usuario o contraseña incorrectos.";
        if ($idUsuario == -1) {
            $_SESSION['error'] = $msj;
            Rutas::irLogin();
        }

        $nuevoUsuario = (!isset($_SESSION['usuario']) || $_SESSION['usuario']['idUsuario'] !== $idUsuario);
        if (!$this->validarSesionBloqueo($idUsuario,$nuevoUsuario) || !$this->validarIntentos($idUsuario, $limiteIntentos,$nuevoUsuario)) {
            Rutas::irLogin();
        }

        $contrasenaHash = $user->ObtenerContraseña($idUsuario);

        if ($contrasenaHash == "" || !password_verify($contrasena, $contrasenaHash)) {
            $_SESSION['error'] = $msj;
            $contadorIntentos = (int)$_SESSION['intentos'] + 1;
            $_SESSION['intentos'] = $contadorIntentos;

            $controlIntentosRepo = new ControlIntentosRepositorio();
            $controlIntentosRepo->ActualizarIntentosUsuario($idUsuario, $contadorIntentos);

            if ($contadorIntentos >= $limiteIntentos) {
                $user->bloquearUsuario($idUsuario, true);
            }
            Rutas::irLogin();
        }
    }

    private function validarIntentos(int $idUsuario, int $limiteIntentos, bool $nuevoUsuario): bool
    {
        $controlIntentosRepo = new ControlIntentosRepositorio();

        if (!isset($_SESSION['intentos']) || $nuevoUsuario) {
            $_SESSION['intentos'] = $controlIntentosRepo->ObtenerIntentosUsuario($idUsuario);
        }

        if ((int) $_SESSION['intentos'] >= $limiteIntentos) {
            $_SESSION['error'] = "Ha excedido el limite de intentos.";
            return false;
        }
        return true;
    }

    private function validarSesionBloqueo(int $idUsuario, bool $nuevoUsuario): bool
    {
        $user = new UsuarioRepositorio();
        $estados = [];

        if(!isset($_SESSION['estadosUsuario']) || $nuevoUsuario){
            $estados = $user->ObtenerEstadosControl($idUsuario);

            if ($estados == []) {
                $_SESSION['error'] = "Intentelo de nuevo.";
                return false;
            }

            $_SESSION['estadosUsuario'] = $estados;
        }

        if ($_SESSION['estadosUsuario']['SesionActiva'] || $_SESSION['estadosUsuario']['Bloqueado']) {
            $_SESSION['error'] = "Ya tiene una sesion activa o su cuenta esta bloqueada.";
            return false;
        }
        return true;
    }
}
