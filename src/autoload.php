<?php
declare(strict_types=1);

spl_autoload_register(function (string $clase): void {
    $prefijo = 'App\\';
    if (strncmp($clase, $prefijo, strlen($prefijo)) !== 0) {
        return;
    }
    $archivo = __DIR__ . '/' . substr($clase, strlen($prefijo)) . '.php';
    if (is_file($archivo)) {
        require $archivo;
    }
});
