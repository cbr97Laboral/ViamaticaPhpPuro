<?php
class Correo
{
    private string $correo;
    private const Extension = "@mail.com";

    public function __construct(string $nombres, string $apellidos)
    {
        $this->correo = $this->generarCorreo($nombres, $apellidos);
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    private function generarCorreo(string $nombres, string $apellidos): string
    {
        $nombresArray = explode(' ', trim($nombres));
        $apellidosArray = explode(' ', trim($apellidos));

        $inicialPrimerNombre = substr($nombresArray[0], 0, 1);
        $primerApellido = $apellidosArray[0];
        $inicialSegundoApellido = isset($apellidosArray[1]) ? substr($apellidosArray[1], 0, 1) : '';

        $correoBase = strtolower($inicialPrimerNombre . $primerApellido . $inicialSegundoApellido . self::Extension);

        return $this->correo = $this->ObtenerCorreoUnico($correoBase);
    }

    private function ObtenerCorreoUnico(string $correo): string
    {
        $correoUnico = UsuarioRepositorio::ObtenerCorreoUnico($correo);
        return $correoUnico;
    }
}
