# Registro de Estudiantes en PHP

Proyecto de PHP hecho con programación orientada a objetos. Sirve para registrar y consultar estudiantes usando peticiones GET y POST. Los estudiantes se guardan en un **mapa** (un arreglo asociativo) donde la clave es el código del estudiante, así no se pueden repetir.

**Autor:** TuNombre TuApellido

## Qué usa el proyecto
- **Herencia:** la clase `Estudiante` hereda de `Persona`.
- **Encapsulamiento:** los atributos son `private` y se usan con getters y setters.
- **Tipos estrictos:** todos los archivos tienen `declare(strict_types=1);`.
- **Mapa:** la clase `MapaEstudiantes` guarda los estudiantes con su código como clave.
- **JSON:** todas las respuestas se devuelven en formato JSON.

El estudiante inicial (mis datos) se carga solo al ejecutar la aplicación, no hay que registrarlo en el formulario.

## Requisitos
- XAMPP con PHP 8.1 o superior
- Un navegador web
- (Opcional) Postman para probar el POST

## Cómo instalarlo y ejecutarlo en XAMPP
1. Descargar o clonar el repositorio.
2. Copiar la carpeta `estudiantes-php` dentro de `htdocs`:
   ```
   C:\xampp\htdocs\estudiantes-php
   ```
3. Abrir el **XAMPP Control Panel** y darle **Start** a **Apache**.
4. Abrir en el navegador:
   ```
   http://localhost/estudiantes-php/
   ```

## Archivos del proyecto
```
estudiantes-php/
├── index.html           -> formulario web
├── api.php              -> recibe las peticiones GET y POST
├── config.php           -> datos del estudiante inicial
├── src/
│   ├── autoload.php
│   ├── Persona.php
│   ├── Estudiante.php
│   ├── MapaEstudiantes.php
│   └── AlmacenJson.php
└── data/                -> aquí se guarda estudiantes.json
```

## Cómo hacer las peticiones

La dirección de la API es:
```
http://localhost/estudiantes-php/api.php
```

### GET - ver todos los estudiantes
Abrir esa dirección en el navegador. Devuelve la lista completa.

### GET - buscar un estudiante por código
```
http://localhost/estudiantes-php/api.php?codigo=TU-CODIGO
```
Si el código no existe devuelve error 404.

### POST - registrar un estudiante
Se envían estos datos: `nombre`, `apellido`, `edad`, `codigo` y `carrera`.

Ejemplo con Postman: método POST, ir a *Body* -> *x-www-form-urlencoded* y llenar:

| Campo | Ejemplo |
|-------|---------|
| nombre | ISABEL |
| apellido | VACA_TICONA |
| edad | 21|
| codigo | 65161094 |
| carrera | ADMINISTRCION|

Ejemplo con curl:
```
curl -X POST http://localhost/estudiantes-php/api.php -d "nombre=Ana&apellido=Perez&edad=20&codigo=2024-001&carrera=Medicina"
```

Respuesta cuando se registra bien:
```json
{
  "exito": true,
  "mensaje": "Estudiante registrado correctamente.",
  "estudiante": {
    "codigo": "65161094",
    "nombre": "ISABEL",
    "apellido": "VACA_TICONA",
    "edad": 21,
    "carrera": "ADMINISTRACION"
  }
}
```

### Códigos de respuesta
| Código | Qué significa |
|--------|---------------|
| 200 | Consulta correcta |
| 201 | Estudiante registrado |
| 400 | Faltan datos o son inválidos |
| 404 | No se encontró el estudiante |
| 409 | El código ya existe (duplicado) |
| 405 | Método no permitido |

## Cómo usar el formulario web
1. Entrar a `http://localhost/estudiantes-php/`.
2. Llenar los cinco campos y presionar **Registrar estudiante**.
3. Abajo, en el cuadro de **Respuesta**, se ve el resultado del POST.
4. Para consultar (GET) se usan los botones **Ver todos** o **Buscar por código**.
5. Si se intenta registrar un código que ya existe, sale el error 409.

## Si algo falla
- Si no guarda los registros, revisar que la carpeta `data` exista y se pueda escribir en ella.
- Si sale un error raro, revisar la versión de PHP con `php -v` (debe ser 8.1 o más).
