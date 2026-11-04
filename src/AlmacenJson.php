<?php
declare(strict_types=1);

namespace App;

use RuntimeException;
use Throwable;

class AlmacenJson
{
    public function __construct(private string $ruta)
    {
    }

    public function cargarEn(MapaEstudiantes $mapa): void
    {
        if (!is_file($this->ruta)) {
            return;
        }
        $datos = json_decode((string) file_get_contents($this->ruta), true);
        if (!is_array($datos)) {
            return;
        }
        foreach ($datos as $d) {
            try {
                $e = new Estudiante(
                    (string) $d['nombre'],
                    (string) $d['apellido'],
                    (int) $d['edad'],
                    (string) $d['codigo'],
                    (string) $d['carrera']
                );
                if (!$mapa->existe($e->getCodigo())) {
                    $mapa->agregar($e);
                }
            } catch (Throwable) {
              
            }
        }
    }

    public function guardar(MapaEstudiantes $mapa): void
    {
        $carpeta = dirname($this->ruta);
        if (!is_dir($carpeta) && !mkdir($carpeta, 0777, true)) {
            throw new RuntimeException('No se pudo crear la carpeta de datos.');
        }
        $lista = array_map(fn(Estudiante $e): array => $e->toArray(), $mapa->todos());
        $json = json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($this->ruta, $json, LOCK_EX) === false) {
            throw new RuntimeException('No se pudo escribir en el archivo de datos.');
        }
    }
}
