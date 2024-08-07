<?php
abstract class Persona
{
    private readonly string $nombres;
    private readonly string $apellidos;
    private readonly string $identificacion;

    protected function __construct(string $nombres, string $apellidos, string $identificacion) {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->identificacion = $identificacion;
    }

    public function getNombres(): string {
        return $this->nombres;
    }

    public function getApellidos(): string {
        return $this->apellidos;
    }

    public function getIdentificacion(): string {
        return $this->identificacion;
    }
}
?>