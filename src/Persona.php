<?php
declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class Persona
{
    public function __construct(
        private string $nombre,
        private string $apellido,
        private int $edad
    ) {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setEdad($edad);
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getEdad(): int
    {
        return $this->edad;
    }

    public function setNombre(string $nombre): void
    {
        $nombre = trim($nombre);
        if ($nombre === '') {
            throw new InvalidArgumentException('El nombre no puede estar vacío.');
        }
        $this->nombre = $nombre;
    }

    public function setApellido(string $apellido): void
    {
        $apellido = trim($apellido);
        if ($apellido === '') {
            throw new InvalidArgumentException('El apellido no puede estar vacío.');
        }
        $this->apellido = $apellido;
    }

    public function setEdad(int $edad): void
    {
        if ($edad < 1 || $edad > 120) {
            throw new InvalidArgumentException('La edad debe estar entre 1 y 120.');
        }
        $this->edad = $edad;
    }

    public function getNombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}
