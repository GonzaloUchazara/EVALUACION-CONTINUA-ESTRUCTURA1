<?php
declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class Estudiante extends Persona
{
    private string $codigo;
    private string $carrera;

    public function __construct(
        string $nombre,
        string $apellido,
        int $edad,
        string $codigo,
        string $carrera
    ) {
        parent::__construct($nombre, $apellido, $edad);

        $codigo = trim($codigo);
        if (!preg_match('/^[A-Za-z0-9\-]{3,20}$/', $codigo)) {
            throw new InvalidArgumentException(
                'El código debe tener entre 3 y 20 caracteres alfanuméricos (se permite "-").'
            );
        }
        $this->codigo = $codigo;
        $this->setCarrera($carrera);
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getCarrera(): string
    {
        return $this->carrera;
    }

    public function setCarrera(string $carrera): void
    {
        $carrera = trim($carrera);
        if ($carrera === '') {
            throw new InvalidArgumentException('La carrera no puede estar vacía.');
        }
        $this->carrera = $carrera;
    }

    public function toArray(): array
    {
        return [
            'codigo'   => $this->codigo,
            'nombre'   => $this->getNombre(),
            'apellido' => $this->getApellido(),
            'edad'     => $this->getEdad(),
            'carrera'  => $this->carrera,
        ];
    }
}
