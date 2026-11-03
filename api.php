<?php
declare(strict_types=1);

require __DIR__ . '/src/autoload.php';

use App\AlmacenJson;
use App\Estudiante;
use App\MapaEstudiantes;

header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, array $cuerpo): never
{
    http_response_code($codigo);
    echo json_encode($cuerpo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

$config = require __DIR__ . '/config.php';
$almacen = new AlmacenJson($config['archivo_datos']);
$mapa = new MapaEstudiantes();


$i = $config['estudiante_inicial'];
$mapa->agregar(new Estudiante($i['nombre'], $i['apellido'], $i['edad'], $i['codigo'], $i['carrera']));


$almacen->cargarEn($mapa);

$metodo = $_SERVER['REQUEST_METHOD'];


if ($metodo === 'GET') {
    if (isset($_GET['codigo'])) {
        $e = $mapa->obtener(trim((string) $_GET['codigo']));
        if ($e === null) {
            responder(404, ['exito' => false, 'mensaje' => 'Estudiante no encontrado.']);
        }
        responder(200, ['exito' => true, 'estudiante' => $e->toArray()]);
    }
    responder(200, [
        'exito' => true,
        'total' => $mapa->total(),
        'estudiantes' => array_map(fn(Estudiante $e): array => $e->toArray(), $mapa->todos()),
    ]);
}


if ($metodo === 'POST') {
    $tipo = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($tipo, 'application/json') !== false) {
        $datos = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($datos)) {
            responder(400, ['exito' => false, 'mensaje' => 'JSON inválido.']);
        }
    } else {
        $datos = $_POST;
    }


    $errores = [];
    foreach (['nombre', 'apellido', 'edad', 'codigo', 'carrera'] as $campo) {
        if (!isset($datos[$campo]) || trim((string) $datos[$campo]) === '') {
            $errores[] = "El campo '$campo' es obligatorio.";
        }
    }
    if ($errores === [] && filter_var($datos['edad'], FILTER_VALIDATE_INT) === false) {
        $errores[] = "El campo 'edad' debe ser un número entero.";
    }
    if ($errores !== []) {
        responder(400, ['exito' => false, 'mensaje' => 'Datos inválidos.', 'errores' => $errores]);
    }

    try {
        $nuevo = new Estudiante(
            (string) $datos['nombre'],
            (string) $datos['apellido'],
            (int) $datos['edad'],
            (string) $datos['codigo'],
            (string) $datos['carrera']
        );
    } catch (InvalidArgumentException $ex) {
        responder(400, ['exito' => false, 'mensaje' => $ex->getMessage()]);
    }

    if ($mapa->existe($nuevo->getCodigo())) {
        responder(409, [
            'exito' => false,
            'mensaje' => "Ya existe un estudiante con el código {$nuevo->getCodigo()}.",
        ]);
    }

    try {
        $mapa->agregar($nuevo);
        $almacen->guardar($mapa);
    } catch (RuntimeException $ex) {
        responder(500, ['exito' => false, 'mensaje' => $ex->getMessage()]);
    }

    responder(201, [
        'exito' => true,
        'mensaje' => 'Estudiante registrado correctamente.',
        'estudiante' => $nuevo->toArray(),
    ]);
}

header('Allow: GET, POST');
responder(405, ['exito' => false, 'mensaje' => 'Método no permitido. Use GET o POST.']);
