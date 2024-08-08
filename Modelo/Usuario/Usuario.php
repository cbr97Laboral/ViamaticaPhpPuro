<?php
require_once __DIR__ . '/../../Modelo/Usuario/Persona.php';
require_once __DIR__ . '/../../Modelo/Usuario/Correo.php';
class Usuario extends Persona{
    private readonly string $userName;
    private Correo $correo;
    private string $contraseña;
    private readonly bool $sesionActiva;
    private readonly bool $bloqueado;

    public function __construct(string $nombres, string $apellidos, string $identificacion, string $userName, string $contraseña, bool $sesionActiva, bool $bloqueado) {
        parent::__construct($nombres, $apellidos, $identificacion);
        
        $this->userName = $userName;
        $this->correo = new Correo($nombres, $apellidos);
        $this->contraseña = $contraseña;
        $this->sesionActiva = $sesionActiva;
        $this->bloqueado = $bloqueado;
    }

    public function getUserName(): string {
        return $this->userName;
    }

    public function getCorreo(): string {
        return $this->correo->getCorreo();
    }

    public function getContraseña(): string {
        return $this->contraseña;
    }

    public function isSesionActiva(): bool {
        return $this->sesionActiva;
    }

    public function isBloqueado(): bool {
        return $this->bloqueado;
    }
}
?>