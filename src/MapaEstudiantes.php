<?php
declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Estructura tipo mapa: clave = código del estudiante, valor = objeto Estudiante.
 */
class MapaEstudiantes
{
    /** @var array<string, Estudiante> */
    private array $estudiantes = [];

    public function existe(string $codigo): bool
    {
        return array_key_exists($codigo, $this->estudiantes);
    }

    public function agregar(Estudiante $estudiante): void
    {
        if ($this->existe($estudiante->getCodigo())) {
            throw new RuntimeException(
                "Ya existe un estudiante con el código {$estudiante->getCodigo()}."
            );
        }
        $this->estudiantes[$estudiante->getCodigo()] = $estudiante;
    }

    public function obtener(string $codigo): ?Estudiante
    {
        return $this->estudiantes[$codigo] ?? null;
    }

    /** @return Estudiante[] */
    public function todos(): array
    {
        return array_values($this->estudiantes);
    }

    public function total(): int
    {
        return count($this->estudiantes);
    }
}
